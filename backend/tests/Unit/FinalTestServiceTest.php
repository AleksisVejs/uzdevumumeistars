<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Topic;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Test;
use App\Models\UserTopicProgress;
use App\Services\FinalTestService;
use App\TopicStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FinalTestServiceTest extends TestCase
{
    use RefreshDatabase;

    private FinalTestService $service;
    private User $user;
    private Topic $topic;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FinalTestService();
        
        $this->user = User::factory()->create();
        $this->topic = Topic::factory()->create([
            'name' => 'Test Topic',
            'next_topic_ids' => [2, 3]
        ]);
    }

    public function test_start_final_test_creates_test_with_questions()
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

        $test = $this->service->startFinalTest($this->user, $this->topic, 2, 600);

        $this->assertInstanceOf(Test::class, $test);
        $this->assertEquals($this->user->id, $test->user_id);
        $this->assertEquals($this->topic->id, $test->topic_id);
        $this->assertEquals('final_topic_test', $test->type);
        $this->assertEquals(2, $test->total_questions);
        $this->assertEquals(600, $test->time_limit_seconds);
        $this->assertNotNull($test->started_at);
    }

    public function test_submit_final_test_calculates_score_correctly()
    {
        // Create test with questions
        $question1 = Question::factory()->create([
            'topic_id' => $this->topic->id,
            'question_text' => 'What is 2+2?'
        ]);
        
        $question2 = Question::factory()->create([
            'topic_id' => $this->topic->id,
            'question_text' => 'What is 5*3?'
        ]);

        $correctAnswer1 = Answer::factory()->create([
            'question_id' => $question1->id,
            'answer_text' => '4',
            'is_correct' => true
        ]);

        $wrongAnswer1 = Answer::factory()->create([
            'question_id' => $question1->id,
            'answer_text' => '3',
            'is_correct' => false
        ]);

        $correctAnswer2 = Answer::factory()->create([
            'question_id' => $question2->id,
            'answer_text' => '15',
            'is_correct' => true
        ]);

        $test = Test::factory()->create([
            'user_id' => $this->user->id,
            'topic_id' => $this->topic->id,
            'type' => 'final_topic_test',
            'total_questions' => 2
        ]);

        // Create test questions
        $test->testQuestions()->create([
            'question_id' => $question1->id,
            'order' => 1
        ]);
        $test->testQuestions()->create([
            'question_id' => $question2->id,
            'order' => 2
        ]);

        // Submit with one correct and one wrong answer
        $answers = [
            $question1->id => $correctAnswer1->id, // Correct
            $question2->id => $wrongAnswer1->id,   // Wrong (using wrong answer from question 1)
        ];

        $result = $this->service->submitFinalTest($test, $answers);

        $this->assertEquals(1, $result->score_correct);
        $this->assertEquals(2, $result->score_total);
        $this->assertFalse($result->passed); // 50% < 70% threshold
        $this->assertNotNull($result->submitted_at);
    }

    public function test_submit_final_test_passes_when_above_threshold()
    {
        // Create test with questions
        $question1 = Question::factory()->create(['topic_id' => $this->topic->id]);
        $question2 = Question::factory()->create(['topic_id' => $this->topic->id]);
        $question3 = Question::factory()->create(['topic_id' => $this->topic->id]);

        $correctAnswer1 = Answer::factory()->create([
            'question_id' => $question1->id,
            'is_correct' => true
        ]);
        $correctAnswer2 = Answer::factory()->create([
            'question_id' => $question2->id,
            'is_correct' => true
        ]);
        $correctAnswer3 = Answer::factory()->create([
            'question_id' => $question3->id,
            'is_correct' => true
        ]);

        $test = Test::factory()->create([
            'user_id' => $this->user->id,
            'topic_id' => $this->topic->id,
            'type' => 'final_topic_test',
            'total_questions' => 3
        ]);

        // Create test questions
        $test->testQuestions()->create(['question_id' => $question1->id, 'order' => 1]);
        $test->testQuestions()->create(['question_id' => $question2->id, 'order' => 2]);
        $test->testQuestions()->create(['question_id' => $question3->id, 'order' => 3]);

        // Submit with all correct answers (100% > 70% threshold)
        $answers = [
            $question1->id => $correctAnswer1->id,
            $question2->id => $correctAnswer2->id,
            $question3->id => $correctAnswer3->id,
        ];

        $result = $this->service->submitFinalTest($test, $answers);

        $this->assertEquals(3, $result->score_correct);
        $this->assertEquals(3, $result->score_total);
        $this->assertTrue($result->passed); // 100% > 70% threshold
    }

    public function test_submit_final_test_updates_user_progress()
    {
        // Create test with questions
        $question = Question::factory()->create(['topic_id' => $this->topic->id]);
        $correctAnswer = Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true
        ]);

        $test = Test::factory()->create([
            'user_id' => $this->user->id,
            'topic_id' => $this->topic->id,
            'type' => 'final_topic_test',
            'total_questions' => 1
        ]);

        $test->testQuestions()->create(['question_id' => $question->id, 'order' => 1]);

        $answers = [$question->id => $correctAnswer->id];

        $this->service->submitFinalTest($test, $answers);

        $progress = UserTopicProgress::where('user_id', $this->user->id)
            ->where('topic_id', $this->topic->id)
            ->first();

        $this->assertNotNull($progress);
        $this->assertEquals(TopicStatus::Passed, $progress->status);
    }

    public function test_get_test_questions_returns_formatted_questions()
    {
        $question = Question::factory()->create([
            'topic_id' => $this->topic->id,
            'question_text' => 'Test question?',
            'difficulty' => 'medium',
            'points' => 2
        ]);

        $answer1 = Answer::factory()->create([
            'question_id' => $question->id,
            'answer_text' => 'Answer 1',
            'order' => 1
        ]);

        $answer2 = Answer::factory()->create([
            'question_id' => $question->id,
            'answer_text' => 'Answer 2',
            'order' => 2
        ]);

        $test = Test::factory()->create([
            'user_id' => $this->user->id,
            'topic_id' => $this->topic->id
        ]);

        $test->testQuestions()->create(['question_id' => $question->id, 'order' => 1]);

        $questions = $this->service->getTestQuestions($test);

        $this->assertCount(1, $questions);
        $this->assertEquals($question->id, $questions[0]['id']);
        $this->assertEquals('Test question?', $questions[0]['question_text']);
        $this->assertEquals('medium', $questions[0]['difficulty']);
        $this->assertEquals(2, $questions[0]['points']);
        $this->assertCount(2, $questions[0]['answers']);
    }
}