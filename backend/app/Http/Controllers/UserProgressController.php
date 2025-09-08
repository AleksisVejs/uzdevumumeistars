<?php

namespace App\Http\Controllers;

use App\Models\UserTopicProgress;
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
        return response()->json([
            'progress' => $progress,
            'xp' => $user->xp,
            'badges' => $badges,
        ]);
    }
}


