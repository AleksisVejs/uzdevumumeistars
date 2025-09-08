<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Topic;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Test;
use App\Services\SelfTestService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SelfTestServiceTest extends TestCase
{
    use RefreshDatabase;

    private SelfTestService $service;
    private User $user;
    private Topic $topic;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SelfTestService();
        
        $this->user = User::factory()->create();
        $this->topic = Topic::factory()->create([
            'name' => 'Test Topic'
        ]);
    }

    public function test_start_self_test_creates_test_without_time_limit()
    {
        // Create test questions
        $question1 = Question::factory()->create([
            'topic_id' => $this->topic->id,
            'question_text' => 'What is 2+2?',
            'difficulty' => 'easy'
        ]);
        
        $question2 = Question::factory()->create([
            'topic_id' => $this->topic->id,
            'question_text' => 'What is 5*3?',
            'difficulty' => 'medium'
        ]);

        Answer::factory()->create([
            'question_id' => $question1->id,
            'answer_text' => '4',
            'is_correct' => true
        ]);

        Answer::factory()->create([
            'question_id' => $question2->id,
            'answer_text' => '15',
            'is_correct' => true
        ]);

        $test = $this->service->startSelfTest($this->user, $this->topic, 2);

        $this->assertInstanceOf(Test::class, $test);
        $this->assertEquals($this->user->id, $test->user_id);
        $this->assertEquals($this->topic->id, $test->topic_id);
        $this->assertEquals('self_test', $test->type);
        $this->assertEquals(2, $test->total_questions);
        $this->assertNull($test->time_limit_seconds); // No time limit for self-tests
        $this->assertNotNull($test->started_at);
    }

    public function test_submit_self_test_calculates_xp_correctly()
    {
        // Create test with questions
        $question1 = Question::factory()->create([
            'topic_id' => $this->topic->id,
            'question_text' => 'What is 2+2?',
            'difficulty' => 'easy',
            'points' => 1
        ]);
        
        $question2 = Question::factory()->create([
            'topic_id' => $this->topic->id,
            'question_text' => 'What is 5*3?',
            'difficulty' => 'hard',
            'points' => 2
        ]);

        $correctAnswer1 = Answer::factory()->create([
            'question_id' => $question1->id,
            'answer_text' => '4',
            'is_correct' => true
        ]);

        $correctAnswer2 = Answer::factory()->create([
            'question_id' => $question2->id,
            'answer_text' => '15',
            'is_correct' => true
        ]);

        $test = Test::factory()->create([
            'user_id' => $this->user->id,
            'topic_id' => $this->topic->id,
            'type' => 'self_test',
            'total_questions' => 2
        ]);

        // Create test questions
        $test->testQuestions()->create(['question_id' => $question1->id, 'order' => 1]);
        $test->testQuestions()->create(['question_id' => $question2->id, 'order' => 2]);

        // Submit with all correct answers
        $answers = [
            $question1->id => $correctAnswer1->id,
            $question2->id => $correctAnswer2->id,
        ];

        $result = $this->service->submitSelfTest($test, $answers);

        $this->assertArrayHasKey('test', $result);
        $this->assertArrayHasKey('xp_awarded', $result);
        $this->assertArrayHasKey('performance_analysis', $result);
        
        $this->assertEquals(2, $result['test']->score_correct);
        $this->assertEquals(2, $result['test']->score_total);
        $this->assertFalse($result['test']->passed); // Self-tests don't have pass/fail
        
        // XP calculation: 10 (base) + 10 (2 correct * 5) + 4 (easy bonus) + 6 (hard bonus) = 30
        $this->assertEquals(30, $result['xp_awarded']);
        
        $this->user->refresh();
        $this->assertEquals(30, $this->user->xp);
    }

    public function test_submit_self_test_generates_performance_analysis()
    {
        // Create test with mixed difficulty questions
        $easyQuestion = Question::factory()->create([
            'topic_id' => $this->topic->id,
            'difficulty' => 'easy'
        ]);
        
        $hardQuestion = Question::factory()->create([
            'topic_id' => $this->topic->id,
            'difficulty' => 'hard'
        ]);

        $easyCorrect = Answer::factory()->create([
            'question_id' => $easyQuestion->id,
            'is_correct' => true
        ]);

        $hardCorrect = Answer::factory()->create([
            'question_id' => $hardQuestion->id,
            'is_correct' => true
        ]);

        $test = Test::factory()->create([
            'user_id' => $this->user->id,
            'topic_id' => $this->topic->id,
            'type' => 'self_test',
            'total_questions' => 2
        ]);

        $test->testQuestions()->create(['question_id' => $easyQuestion->id, 'order' => 1]);
        $test->testQuestions()->create(['question_id' => $hardQuestion->id, 'order' => 2]);

        $answers = [
            $easyQuestion->id => $easyCorrect->id,
            $hardQuestion->id => $hardCorrect->id,
        ];

        $result = $this->service->submitSelfTest($test, $answers);

        $analysis = $result['performance_analysis'];
        
        $this->assertEquals(2, $analysis['total_questions']);
        $this->assertEquals(2, $analysis['correct_answers']);
        $this->assertEquals(1, $analysis['difficulty_breakdown']['easy']);
        $this->assertEquals(1, $analysis['difficulty_breakdown']['hard']);
        $this->assertContains('Easy jautājumi', $analysis['strengths']);
        $this->assertContains('Hard jautājumi', $analysis['strengths']);
    }

    public function test_adaptive_question_selection_based_on_performance()
    {
        // Create questions of different difficulties
        $easyQuestions = Question::factory()->count(5)->create([
            'topic_id' => $this->topic->id,
            'difficulty' => 'easy'
        ]);
        
        $mediumQuestions = Question::factory()->count(5)->create([
            'topic_id' => $this->topic->id,
            'difficulty' => 'medium'
        ]);
        
        $hardQuestions = Question::factory()->count(5)->create([
            'topic_id' => $this->topic->id,
            'difficulty' => 'hard'
        ]);

        // Create answers for all questions
        foreach ($easyQuestions->merge($mediumQuestions)->merge($hardQuestions) as $question) {
            Answer::factory()->create([
                'question_id' => $question->id,
                'is_correct' => true
            ]);
        }

        // Start a self-test - should select questions adaptively
        $test = $this->service->startSelfTest($this->user, $this->topic, 10);

        $this->assertInstanceOf(Test::class, $test);
        $this->assertEquals(10, $test->total_questions);
        
        // Verify test questions were created
        $this->assertEquals(10, $test->testQuestions()->count());
    }

    public function test_grade_filtering_in_question_selection()
    {
        // Create questions for different grades
        $grade7Question = Question::factory()->create([
            'topic_id' => $this->topic->id,
            'grade' => 7,
            'question_text' => 'Grade 7 question'
        ]);
        
        $grade10Question = Question::factory()->create([
            'topic_id' => $this->topic->id,
            'grade' => 10,
            'question_text' => 'Grade 10 question'
        ]);

        Answer::factory()->create(['question_id' => $grade7Question->id, 'is_correct' => true]);
        Answer::factory()->create(['question_id' => $grade10Question->id, 'is_correct' => true]);

        // Start self-test for grade 7 only
        $test = $this->service->startSelfTest($this->user, $this->topic, 1, 7);

        $this->assertEquals(1, $test->total_questions);
        
        // Should only include grade 7 question
        $testQuestion = $test->testQuestions()->with('question')->first();
        $this->assertEquals(7, $testQuestion->question->grade);
    }
}