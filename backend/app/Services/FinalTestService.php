<?php

namespace App\Services;

use App\Models\Test;
use App\Models\Topic;
use App\Models\User;
use App\Models\UserTopicProgress;
use App\Models\Badge;
use App\Models\Question;
use App\Models\TestQuestion;
use App\TopicStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinalTestService
{
    public const PASS_THRESHOLD_PERCENT = 70;

    public function startFinalTest(User $user, Topic $topic, int $questionCount, ?int $timeLimitSeconds = null, ?int $grade = null): Test
    {
        return DB::transaction(function () use ($user, $topic, $questionCount, $timeLimitSeconds, $grade) {
            // Select random questions from the topic, optionally filtered by grade
            $query = Question::where('topic_id', $topic->id)
                ->where('is_active', true);
            
            if ($grade) {
                $query->where('grade', $grade);
            }
            
            $questions = $query->inRandomOrder()
                ->limit($questionCount)
                ->get();

            if ($questions->isEmpty()) {
                throw new \Exception("Nav pieejamu jautājumu šai tēmai.");
            }

            $actualQuestionCount = $questions->count();

            /** @var Test $test */
            $test = Test::create([
                'user_id' => $user->id,
                'topic_id' => $topic->id,
                'type' => 'final_topic_test',
                'total_questions' => $actualQuestionCount,
                'time_limit_seconds' => $timeLimitSeconds,
                'started_at' => Carbon::now(),
            ]);

            // Create test questions
            foreach ($questions as $index => $question) {
                TestQuestion::create([
                    'test_id' => $test->id,
                    'question_id' => $question->id,
                    'order' => $index + 1,
                ]);
            }

            // Ensure user topic progress row exists
            $progress = UserTopicProgress::firstOrCreate(
                ['user_id' => $user->id, 'topic_id' => $topic->id],
                ['status' => TopicStatus::Unlocked, 'attempts' => 0]
            );
            $progress->increment('attempts');

            return $test;
        });
    }

    public function submitFinalTest(Test $test, array $answers): Test
    {
        return DB::transaction(function () use ($test, $answers) {
            // Get test questions with correct answers
            $testQuestions = $test->testQuestions()->with(['question.answers'])->get();
            
            $scoreCorrect = 0;
            $scoreTotal = $testQuestions->count();
            $detailedResults = [];

            foreach ($testQuestions as $testQuestion) {
                $question = $testQuestion->question;
                $userAnswerId = $answers[$question->id] ?? null;
                
                // Find the correct answer for this question
                $correctAnswer = $question->answers->where('is_correct', true)->first();
                
                $isCorrect = $userAnswerId && $correctAnswer && $userAnswerId == $correctAnswer->id;
                
                if ($isCorrect) {
                    $scoreCorrect++;
                }
                
                $detailedResults[] = [
                    'question_id' => $question->id,
                    'user_answer_id' => $userAnswerId,
                    'correct_answer_id' => $correctAnswer->id,
                    'is_correct' => $isCorrect,
                    'points' => $question->points,
                ];
            }

            $passed = $scoreTotal > 0 ? (($scoreCorrect / $scoreTotal) * 100 >= self::PASS_THRESHOLD_PERCENT) : false;

            $test->update([
                'score_correct' => $scoreCorrect,
                'score_total' => $scoreTotal,
                'passed' => $passed,
                'submitted_at' => Carbon::now(),
            ]);

            // Update progress
            $progress = UserTopicProgress::firstOrCreate(
                ['user_id' => $test->user_id, 'topic_id' => $test->topic_id],
                ['status' => TopicStatus::Unlocked, 'attempts' => 0]
            );
            $progress->status = $passed ? TopicStatus::Passed : TopicStatus::Failed;
            $progress->save();

            // Unlock next topics if passed
            if ($passed) {
                $this->unlockNextTopics($test->user, $test->topic);
                $this->awardXpAndBadge($test->user, $test->topic);
            }

            // Store detailed results in test metadata
            $test->update(['metadata' => ['detailed_results' => $detailedResults]]);

            return $test;
        });
    }

    protected function unlockNextTopics(User $user, Topic $topic): void
    {
        $nextIds = $topic->next_topic_ids ?? [];
        if (!is_array($nextIds) || empty($nextIds)) {
            return;
        }
        foreach ($nextIds as $nextId) {
            UserTopicProgress::firstOrCreate(
                ['user_id' => $user->id, 'topic_id' => (int) $nextId],
                ['status' => TopicStatus::Unlocked, 'attempts' => 0]
            );
        }
    }

    public function getTestQuestions(Test $test): array
    {
        return $test->testQuestions()
            ->with(['question.answers'])
            ->orderBy('order')
            ->get()
            ->map(function ($testQuestion) {
                return [
                    'id' => $testQuestion->question->id,
                    'order' => $testQuestion->order,
                    'question_text' => $testQuestion->question->question_text,
                    'difficulty' => $testQuestion->question->difficulty,
                    'points' => $testQuestion->question->points,
                    'answers' => $testQuestion->question->answers->map(function ($answer) {
                        return [
                            'id' => $answer->id,
                            'answer_text' => $answer->answer_text,
                            'order' => $answer->order,
                        ];
                    }),
                ];
            })
            ->toArray();
    }

    protected function awardXpAndBadge(User $user, Topic $topic): void
    {
        // XP award: 100 XP per final topic pass (adjust as needed)
        $user->increment('xp', 100);

        // Badge: one per topic, code "topic_{id}_completed"
        $code = 'topic_' . $topic->id . '_completed';
        $badge = Badge::firstOrCreate(
            ['code' => $code],
            ['name' => 'Pabeigta tēma: ' . $topic->name, 'description' => 'Nokārtots tēmas gala tests']
        );
        if (!$user->badges()->where('badge_id', $badge->id)->exists()) {
            $user->badges()->attach($badge->id, ['awarded_at' => Carbon::now()]);
        }
    }
}


