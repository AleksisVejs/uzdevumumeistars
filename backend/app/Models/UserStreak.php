<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class UserStreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'current_streak',
        'longest_streak',
        'last_activity_date'
    ];

    protected $casts = [
        'last_activity_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function updateStreak(int $userId, string $type = 'learning'): self
    {
        $streak = self::firstOrCreate(
            ['user_id' => $userId, 'type' => $type],
            [
                'current_streak' => 0,
                'longest_streak' => 0,
                'last_activity_date' => null
            ]
        );

        $today = Carbon::today();
        $lastActivity = $streak->last_activity_date ? Carbon::parse($streak->last_activity_date) : null;

        if (!$lastActivity) {
            // First activity
            $streak->current_streak = 1;
            $streak->longest_streak = 1;
        } elseif ($lastActivity->isToday()) {
            // Already updated today, no change
            return $streak;
        } elseif ($lastActivity->isYesterday()) {
            // Consecutive day
            $streak->current_streak += 1;
            $streak->longest_streak = max($streak->longest_streak, $streak->current_streak);
        } else {
            // Streak broken
            $streak->current_streak = 1;
        }

        $streak->last_activity_date = $today;
        $streak->save();

        return $streak;
    }

    public static function getCurrentStreak(int $userId, string $type = 'learning'): int
    {
        $streak = self::where('user_id', $userId)
            ->where('type', $type)
            ->first();

        if (!$streak) {
            return 0;
        }

        $lastActivity = $streak->last_activity_date ? Carbon::parse($streak->last_activity_date) : null;
        
        if (!$lastActivity || $lastActivity->isBefore(Carbon::yesterday())) {
            return 0; // Streak is broken
        }

        return $streak->current_streak;
    }
}