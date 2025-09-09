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
        $topics = Topic::query()
            ->whereHas('userProgress', function ($q) use ($user) {
                $q->where('user_id', $user->id)->whereIn('status', [TopicStatus::Unlocked->value, TopicStatus::Passed->value]);
            })
            ->orWhereDoesntHave('userProgress')
            ->get();

        return response()->json($topics);
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
}


