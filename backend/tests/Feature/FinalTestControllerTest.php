<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Topic;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class FinalTestControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Topic $topic;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->topic = Topic::factory()->create([
            'name' => 'Test Topic'
        ]);
        
        Sanctum::actingAs($this->user);
    }

    public function test_can_start_final_test()
    {
        // Create test questions
        $question1 = Question::factory()->create([
            'topic_id' => $this->topic->id,
            'question_text' => 'What is 2+2?'
        ]);
        
        $question2 = Question::factory()->create([
            'topic_id' => $this->topic->id,
            'question_text' => 'What is 5*3?'
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

        $response = $this->postJson('/api/tests/final', [
            'topic_id' => $this->topic->id,
            'question_count' => 2,
            'time_limit_seconds' => 600,
            'grade' => 7
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'user_id',
                'topic_id',
                'type',
                'total_questions',
                'time_limit_seconds',
                'started_at'
            ]);

        $this->assertDatabaseHas('tests', [
            'user_id' => $this->user->id,
            'topic_id' => $this->topic->id,
            'type' => 'final_topic_test',
            'total_questions' => 2,
            'time_limit_seconds' => 600
        ]);
    }

    public function test_can_get_test_questions()
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
            'topic_id' => $this->topic->id,
            'type' => 'final_topic_test'
        ]);

        $test->testQuestions()->create([
            'question_id' => $question->id,
            'order' => 1
        ]);

        $response = $this->getJson("/api/tests/{$test->id}/questions");

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'order',
                    'question_text',
                    'difficulty',
                    'points',
                    'answers' => [
                        '*' => [
                            'id',
                            'answer_text',
                            'order'
                        ]
                    ]
                ]
            ]);

        $responseData = $response->json();
        $this->assertCount(1, $responseData);
        $this->assertEquals($question->id, $responseData[0]['id']);
        $this->assertEquals('Test question?', $responseData[0]['question_text']);
        $this->assertCount(2, $responseData[0]['answers']);
    }

    public function test_can_submit_final_test()
    {
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

        $test->testQuestions()->create(['question_id' => $question1->id, 'order' => 1]);
        $test->testQuestions()->create(['question_id' => $question2->id, 'order' => 2]);

        $response = $this->postJson("/api/tests/{$test->id}/final/submit", [
            'answers' => [
                $question1->id => $correctAnswer1->id,
                $question2->id => $correctAnswer2->id,
            ]
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'score_correct',
                'score_total',
                'passed',
                'submitted_at'
            ]);

        $responseData = $response->json();
        $this->assertEquals(2, $responseData['score_correct']);
        $this->assertEquals(2, $responseData['score_total']);
        $this->assertTrue($responseData['passed']); // 100% > 70% threshold
    }

    public function test_validation_errors_on_invalid_input()
    {
        // Test missing topic_id
        $response = $this->postJson('/api/tests/final', [
            'question_count' => 5
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['topic_id']);

        // Test invalid topic_id
        $response = $this->postJson('/api/tests/final', [
            'topic_id' => 999,
            'question_count' => 5
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['topic_id']);

        // Test invalid question_count
        $response = $this->postJson('/api/tests/final', [
            'topic_id' => $this->topic->id,
            'question_count' => 0
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['question_count']);
    }

    public function test_unauthorized_access_requires_authentication()
    {
        // Test without authentication
        auth()->logout();

        $response = $this->postJson('/api/tests/final', [
            'topic_id' => $this->topic->id,
            'question_count' => 5
        ]);

        $response->assertStatus(401);
    }

    public function test_cannot_access_other_users_test()
    {
        $otherUser = User::factory()->create();
        $otherTest = Test::factory()->create([
            'user_id' => $otherUser->id,
            'topic_id' => $this->topic->id
        ]);

        $response = $this->getJson("/api/tests/{$otherTest->id}/questions");

        $response->assertStatus(403);
    }
}