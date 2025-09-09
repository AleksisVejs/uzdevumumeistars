<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get user profile data
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user statistics
        $completedTests = DB::table('tests')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $currentStreak = $this->calculateCurrentStreak($user->id);
        
        // Get recent activity (last 10 activities)
        $recentActivity = DB::table('tests')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($test) {
                return [
                    'id' => $test->id,
                    'type' => 'test_completed',
                    'description' => $this->getTestDescription($test),
                    'xp_gained' => $test->xp_gained ?? 0,
                    'created_at' => $test->updated_at
                ];
            });

        // Get user badges
        $badges = DB::table('user_badges')
            ->join('badges', 'user_badges.badge_id', '=', 'badges.id')
            ->where('user_badges.user_id', $user->id)
            ->select('badges.*')
            ->get();

        return response()->json([
            'user' => $user,
            'completed_tests' => $completedTests,
            'current_streak' => $currentStreak,
            'recent_activity' => $recentActivity,
            'badges' => $badges
        ]);
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'email']));

        return response()->json([
            'message' => 'Profils atjaunināts veiksmīgi',
            'user' => $user
        ]);
    }

    /**
     * Calculate current streak
     */
    private function calculateCurrentStreak($userId)
    {
        $streak = 0;
        $currentDate = now()->toDateString();
        
        // Get all completed tests ordered by date
        $completedTests = DB::table('tests')
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($completedTests as $test) {
            $testDate = \Carbon\Carbon::parse($test->updated_at)->toDateString();
            
            if ($testDate === $currentDate) {
                $streak++;
                $currentDate = \Carbon\Carbon::parse($currentDate)->subDay()->toDateString();
            } else {
                break;
            }
        }

        return $streak;
    }

    /**
     * Get test description for activity feed
     */
    private function getTestDescription($test)
    {
        $testType = $test->test_type === 'final' ? 'Gala tests' : 'Pašpārbaude';
        $topicName = 'Nezināma tēma';
        
        if ($test->topic_id) {
            $topic = DB::table('topics')->find($test->topic_id);
            if ($topic) {
                $topicName = $topic->name;
            }
        }

        return "Pabeidzts {$testType} - {$topicName}";
    }
}
