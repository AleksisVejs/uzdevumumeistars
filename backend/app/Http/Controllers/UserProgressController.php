<?php

namespace App\Http\Controllers;

use App\Models\UserTopicProgress;
use App\Models\UserActivity;
use App\Models\UserStreak;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;
use App\Models\Badge;

class UserProgressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $progress = UserTopicProgress::with('topic')
            ->where('user_id', $user->id)
            ->get();
        $badges = $user->badges()->get();
        
        // Get current streak
        $currentStreak = UserStreak::getCurrentStreak($user->id);
        
        // Get total tests completed
        $totalTestsCompleted = Test::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
        
        // Get recent activity (last 10 activities)
        $recentActivity = UserActivity::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'type' => $activity->type,
                    'description' => $activity->description,
                    'xp' => $activity->xp_earned,
                    'created_at' => $activity->created_at
                ];
            });

        return response()->json([
            'progress' => $progress,
            'xp' => $user->xp,
            'badges' => $badges,
            'currentStreak' => $currentStreak,
            'totalTestsCompleted' => $totalTestsCompleted,
            'recentActivity' => $recentActivity,
        ]);
    }
}


