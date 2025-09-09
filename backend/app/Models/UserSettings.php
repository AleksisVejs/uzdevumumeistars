<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_notifications',
        'test_reminders',
        'weekly_progress_emails',
        'achievement_notifications',
        'language',
        'timezone',
        'preferences'
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'test_reminders' => 'boolean',
        'weekly_progress_emails' => 'boolean',
        'achievement_notifications' => 'boolean',
        'preferences' => 'array'
    ];

    /**
     * Get the user that owns the settings
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get or create settings for a user
     */
    public static function getForUser($userId): self
    {
        return static::firstOrCreate(
            ['user_id' => $userId],
            [
                'email_notifications' => true,
                'test_reminders' => true,
                'weekly_progress_emails' => true,
                'achievement_notifications' => true,
                'language' => 'lv',
                'timezone' => 'Europe/Riga',
                'preferences' => []
            ]
        );
    }
}