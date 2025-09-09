<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsService
{
    /**
     * Get user settings with defaults
     */
    public function getUserSettings(User $user): array
    {
        $settings = UserSettings::getForUser($user->id);
        
        return [
            'user' => $user,
            'notification_settings' => [
                'email_notifications' => $settings->email_notifications,
                'test_reminders' => $settings->test_reminders,
                'weekly_progress_emails' => $settings->weekly_progress_emails,
                'achievement_notifications' => $settings->achievement_notifications,
            ],
            'preferences' => [
                'language' => $settings->language,
                'timezone' => $settings->timezone,
                'custom_preferences' => $settings->preferences ?? []
            ]
        ];
    }

    /**
     * Update account information
     */
    public function updateAccount(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email']
        ]);

        return $user->fresh();
    }

    /**
     * Update password
     */
    public function updatePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        if (!Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        return true;
    }

    /**
     * Update notification settings
     */
    public function updateNotificationSettings(User $user, array $settings): UserSettings
    {
        $userSettings = UserSettings::getForUser($user->id);
        
        $userSettings->update([
            'email_notifications' => $settings['email_notifications'] ?? $userSettings->email_notifications,
            'test_reminders' => $settings['test_reminders'] ?? $userSettings->test_reminders,
            'weekly_progress_emails' => $settings['weekly_progress_emails'] ?? $userSettings->weekly_progress_emails,
            'achievement_notifications' => $settings['achievement_notifications'] ?? $userSettings->achievement_notifications,
        ]);

        return $userSettings;
    }

    /**
     * Update user preferences
     */
    public function updatePreferences(User $user, array $preferences): UserSettings
    {
        $userSettings = UserSettings::getForUser($user->id);
        
        $userSettings->update([
            'language' => $preferences['language'] ?? $userSettings->language,
            'timezone' => $preferences['timezone'] ?? $userSettings->timezone,
            'preferences' => array_merge($userSettings->preferences ?? [], $preferences['custom_preferences'] ?? [])
        ]);

        return $userSettings;
    }

    /**
     * Delete user account and all related data
     */
    public function deleteAccount(User $user): bool
    {
        try {
            DB::transaction(function () use ($user) {
                // Delete user settings
                $user->settings()?->delete();
                
                // Delete user badges
                $user->badges()->detach();
                
                // Delete user topic progress
                $user->topicProgress()->delete();
                
                // Delete user tests
                $user->tests()->delete();
                
                // Delete user activities
                $user->activities()?->delete();
                
                // Delete user streaks
                $user->streaks()?->delete();
                
                // Revoke all tokens
                $user->tokens()->delete();
                
                // Finally delete the user
                $user->delete();
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to delete user account: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Export user data
     */
    public function exportUserData(User $user): array
    {
        $userData = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'xp' => $user->xp,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'settings' => $user->settings ? [
                'email_notifications' => $user->settings->email_notifications,
                'test_reminders' => $user->settings->test_reminders,
                'weekly_progress_emails' => $user->settings->weekly_progress_emails,
                'achievement_notifications' => $user->settings->achievement_notifications,
                'language' => $user->settings->language,
                'timezone' => $user->settings->timezone,
                'preferences' => $user->settings->preferences,
            ] : null,
            'tests' => $user->tests()->with('topic')->get()->map(function ($test) {
                return [
                    'id' => $test->id,
                    'test_type' => $test->test_type,
                    'topic_name' => $test->topic?->name,
                    'status' => $test->status,
                    'score' => $test->score,
                    'xp_gained' => $test->xp_gained,
                    'created_at' => $test->created_at,
                    'updated_at' => $test->updated_at,
                ];
            }),
            'topic_progress' => $user->topicProgress()->with('topic')->get()->map(function ($progress) {
                return [
                    'topic_name' => $progress->topic?->name,
                    'status' => $progress->status,
                    'completed_at' => $progress->completed_at,
                    'created_at' => $progress->created_at,
                    'updated_at' => $progress->updated_at,
                ];
            }),
            'badges' => $user->badges()->get()->map(function ($badge) {
                return [
                    'id' => $badge->id,
                    'name' => $badge->name,
                    'description' => $badge->description,
                    'icon' => $badge->icon,
                    'earned_at' => $badge->pivot->created_at,
                ];
            }),
            'statistics' => $this->getUserStatistics($user),
            'exported_at' => now()->toISOString(),
            'export_version' => '1.0'
        ];

        return $userData;
    }

    /**
     * Get user statistics
     */
    private function getUserStatistics(User $user): array
    {
        $completedTests = $user->tests()->where('status', 'completed')->count();
        $totalXp = $user->xp;
        $currentStreak = $this->calculateCurrentStreak($user);
        $topicsCompleted = $user->topicProgress()->where('status', 'completed')->count();
        $badgesEarned = $user->badges()->count();

        return [
            'completed_tests' => $completedTests,
            'total_xp' => $totalXp,
            'current_streak' => $currentStreak,
            'topics_completed' => $topicsCompleted,
            'badges_earned' => $badgesEarned,
        ];
    }

    /**
     * Calculate current streak
     */
    private function calculateCurrentStreak(User $user): int
    {
        $streak = 0;
        $currentDate = now()->toDateString();
        
        $completedTests = $user->tests()
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
     * Clear user cache
     */
    public function clearUserCache(User $user): bool
    {
        try {
            // Clear any cached data related to the user
            // This could include clearing Redis cache, file cache, etc.
            
            // For now, we'll just return true
            // In the future, you can implement actual cache clearing logic
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to clear user cache: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get available languages
     */
    public function getAvailableLanguages(): array
    {
        return [
            'lv' => 'Latviešu',
            'en' => 'English',
            'ru' => 'Русский'
        ];
    }

    /**
     * Get available timezones
     */
    public function getAvailableTimezones(): array
    {
        return [
            'Europe/Riga' => 'Riga (UTC+2/+3)',
            'Europe/London' => 'London (UTC+0/+1)',
            'Europe/Berlin' => 'Berlin (UTC+1/+2)',
            'America/New_York' => 'New York (UTC-5/-4)',
            'Asia/Tokyo' => 'Tokyo (UTC+9)',
        ];
    }
}
