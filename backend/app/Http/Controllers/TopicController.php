<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Question;
use App\TopicStatus;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    public function unlocked()
    {
        $user = Auth::user();
        
        // Get all topics
        $allTopics = Topic::all();
        $unlockedTopics = collect();
        
        foreach ($allTopics as $topic) {
            $progress = \App\Models\UserTopicProgress::where('user_id', $user->id)
                ->where('topic_id', $topic->id)
                ->first();
            
            if (!$progress) {
                // No progress record exists - check if this should be unlocked by default
                if ($topic->prerequisite_level == 1) {
                    // First level topics are unlocked by default
                    $unlockedTopics->push($topic);
                }
            } else {
                // Progress record exists - check if unlocked or passed
                if (in_array($progress->status, [TopicStatus::Unlocked->value, TopicStatus::Passed->value])) {
                    $unlockedTopics->push($topic);
                }
            }
        }

        return response()->json($unlockedTopics);
    }

    public function all()
    {
        $topics = Topic::orderBy('prerequisite_level')->get();
        return response()->json($topics);
    }

    public function show(Topic $topic)
    {
        $topic->load('questions');
        return response()->json($topic);
    }

    public function hasQuestionsForGrade(Topic $topic, $grade)
    {
        $hasQuestions = Question::where('topic_id', $topic->id)
            ->where('grade', $grade)
            ->where('is_active', true)
            ->exists();

        return response()->json([
            'has_questions' => $hasQuestions,
            'question_count' => $hasQuestions ? Question::where('topic_id', $topic->id)
                ->where('grade', $grade)
                ->where('is_active', true)
                ->count() : 0
        ]);
    }

    public function practiceTestsStatus(Topic $topic)
    {
        $user = Auth::user();
        
        // Check if user has completed practice tests for each grade
        $grades = [7, 10];
        $status = [];
        
        foreach ($grades as $grade) {
            $hasCompleted = \App\Models\Test::where('topic_id', $topic->id)
                ->where('grade', $grade)
                ->where('test_type', 'self')
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->exists();
                
            $status[$grade] = $hasCompleted;
        }
        
        return response()->json($status);
    }
}


