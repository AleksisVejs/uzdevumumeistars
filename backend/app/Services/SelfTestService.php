<?php

namespace App\Services;

use App\Models\Test;
use App\Models\Topic;
use App\Models\User;
use App\Models\UserTopicProgress;
use App\Models\Question;
use App\Models\TestQuestion;
use App\TopicStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SelfTestService
{
    public function startSelfTest(User $user, Topic $topic, int $questionCount = 10, ?int $grade = null): Test
    {
        return DB::transaction(function () use ($user, $topic, $questionCount, $grade) {
            // Select adaptive questions based on user's performance history
            $questions = $this->selectAdaptiveQuestions($user, $topic, $questionCount, $grade);

            if ($questions->isEmpty()) {
                throw new \Exception("Nav pieejamu jautājumu šai tēmai.");
            }

            $actualQuestionCount = $questions->count();

            /** @var Test $test */
            $test = Test::create([
                'user_id' => $user->id,
                'topic_id' => $topic->id,
                'type' => 'self_test',
                'total_questions' => $actualQuestionCount,
                'time_limit_seconds' => null, // Self-tests have no time limit
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

            return $test;
        });
    }

    public function submitSelfTest(Test $test, array $answers): array
    {
        return DB::transaction(function () use ($test, $answers) {
            // Get test questions with correct answers
            $testQuestions = $test->testQuestions()->with(['question.answers'])->get();
            
            $scoreCorrect = 0;
            $scoreTotal = $testQuestions->count();
            $detailedResults = [];
            $difficultyAnalysis = ['easy' => 0, 'medium' => 0, 'hard' => 0];

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
                    'difficulty' => $question->difficulty,
                    'points' => $question->points,
                ];

                // Track difficulty performance
                $difficultyAnalysis[$question->difficulty]++;
            }

            $percentage = $scoreTotal > 0 ? ($scoreCorrect / $scoreTotal) * 100 : 0;

            $test->update([
                'score_correct' => $scoreCorrect,
                'score_total' => $scoreTotal,
                'passed' => false, // Self-tests don't have pass/fail
                'submitted_at' => Carbon::now(),
                'metadata' => [
                    'detailed_results' => $detailedResults,
                    'difficulty_analysis' => $difficultyAnalysis,
                    'percentage' => $percentage,
                ]
            ]);

            // Award XP based on performance
            $xpAwarded = $this->calculateXpAward($scoreCorrect, $scoreTotal, $detailedResults);
            $user = $test->user;
            $user->increment('xp', $xpAwarded);

            return [
                'test' => $test,
                'xp_awarded' => $xpAwarded,
                'performance_analysis' => $this->generatePerformanceAnalysis($detailedResults),
            ];
        });
    }

    private function selectAdaptiveQuestions(User $user, Topic $topic, int $questionCount, ?int $grade = null)
    {
        // Get user's performance history for this topic
        $userPerformance = $this->getUserPerformanceHistory($user, $topic);
        
        // Determine difficulty distribution based on performance
        $difficultyDistribution = $this->calculateDifficultyDistribution($userPerformance);
        
        $questions = collect();
        
        // Select questions based on difficulty distribution
        foreach ($difficultyDistribution as $difficulty => $count) {
            if ($count > 0) {
                $query = Question::where('topic_id', $topic->id)
                    ->where('is_active', true)
                    ->where('difficulty', $difficulty);
                
                if ($grade) {
                    $query->where('grade', $grade);
                }
                
                $difficultyQuestions = $query->inRandomOrder()
                    ->limit($count)
                    ->get();
                
                $questions = $questions->merge($difficultyQuestions);
            }
        }
        
        // If we don't have enough questions, fill with random ones
        if ($questions->count() < $questionCount) {
            $remaining = $questionCount - $questions->count();
            $query = Question::where('topic_id', $topic->id)
                ->where('is_active', true)
                ->whereNotIn('id', $questions->pluck('id'));
            
            if ($grade) {
                $query->where('grade', $grade);
            }
            
            $additionalQuestions = $query->inRandomOrder()
                ->limit($remaining)
                ->get();
            
            $questions = $questions->merge($additionalQuestions);
        }
        
        return $questions->take($questionCount);
    }

    private function getUserPerformanceHistory(User $user, Topic $topic): array
    {
        // Get recent self-test performance for this topic
        $recentTests = Test::where('user_id', $user->id)
            ->where('topic_id', $topic->id)
            ->where('type', 'self_test')
            ->whereNotNull('submitted_at')
            ->orderBy('submitted_at', 'desc')
            ->limit(5)
            ->get();

        $performance = [
            'easy' => ['correct' => 0, 'total' => 0],
            'medium' => ['correct' => 0, 'total' => 0],
            'hard' => ['correct' => 0, 'total' => 0],
        ];

        foreach ($recentTests as $test) {
            $metadata = $test->metadata ?? [];
            $detailedResults = $metadata['detailed_results'] ?? [];
            
            foreach ($detailedResults as $result) {
                $difficulty = $result['difficulty'] ?? 'medium';
                $performance[$difficulty]['total']++;
                if ($result['is_correct']) {
                    $performance[$difficulty]['correct']++;
                }
            }
        }

        return $performance;
    }

    private function calculateDifficultyDistribution(array $performance): array
    {
        $totalQuestions = 10; // Default self-test size
        
        // Calculate success rates
        $successRates = [];
        foreach ($performance as $difficulty => $stats) {
            if ($stats['total'] > 0) {
                $successRates[$difficulty] = $stats['correct'] / $stats['total'];
            } else {
                $successRates[$difficulty] = 0.5; // Default to 50% for new users
            }
        }

        // Adaptive distribution based on performance
        $distribution = ['easy' => 0, 'medium' => 0, 'hard' => 0];
        
        if ($successRates['easy'] < 0.7) {
            // User struggles with easy questions, give more easy ones
            $distribution['easy'] = 6;
            $distribution['medium'] = 3;
            $distribution['hard'] = 1;
        } elseif ($successRates['medium'] < 0.6) {
            // User struggles with medium questions, balanced approach
            $distribution['easy'] = 3;
            $distribution['medium'] = 5;
            $distribution['hard'] = 2;
        } else {
            // User is doing well, challenge them with harder questions
            $distribution['easy'] = 2;
            $distribution['medium'] = 4;
            $distribution['hard'] = 4;
        }

        return $distribution;
    }

    private function calculateXpAward(int $scoreCorrect, int $scoreTotal, array $detailedResults): int
    {
        $baseXp = 10; // Base XP for completing a self-test
        $correctXp = $scoreCorrect * 5; // 5 XP per correct answer
        
        // Bonus XP for harder questions
        $difficultyBonus = 0;
        foreach ($detailedResults as $result) {
            if ($result['is_correct']) {
                switch ($result['difficulty']) {
                    case 'easy':
                        $difficultyBonus += 1;
                        break;
                    case 'medium':
                        $difficultyBonus += 2;
                        break;
                    case 'hard':
                        $difficultyBonus += 3;
                        break;
                }
            }
        }

        return $baseXp + $correctXp + $difficultyBonus;
    }

    private function generatePerformanceAnalysis(array $detailedResults): array
    {
        $analysis = [
            'total_questions' => count($detailedResults),
            'correct_answers' => 0,
            'difficulty_breakdown' => ['easy' => 0, 'medium' => 0, 'hard' => 0],
            'strengths' => [],
            'weaknesses' => [],
        ];

        foreach ($detailedResults as $result) {
            if ($result['is_correct']) {
                $analysis['correct_answers']++;
            }
            
            $analysis['difficulty_breakdown'][$result['difficulty']]++;
        }

        // Identify strengths and weaknesses
        $difficultyCorrect = ['easy' => 0, 'medium' => 0, 'hard' => 0];
        foreach ($detailedResults as $result) {
            if ($result['is_correct']) {
                $difficultyCorrect[$result['difficulty']]++;
            }
        }

        foreach (['easy', 'medium', 'hard'] as $difficulty) {
            $total = $analysis['difficulty_breakdown'][$difficulty];
            $correct = $difficultyCorrect[$difficulty];
            
            if ($total > 0) {
                $rate = $correct / $total;
                if ($rate >= 0.8) {
                    $analysis['strengths'][] = ucfirst($difficulty) . ' jautājumi';
                } elseif ($rate <= 0.4) {
                    $analysis['weaknesses'][] = ucfirst($difficulty) . ' jautājumi';
                }
            }
        }

        return $analysis;
    }
}
