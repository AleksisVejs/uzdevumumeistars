<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use App\Services\SettingsService;

class SettingsController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->middleware('auth:sanctum');
        $this->settingsService = $settingsService;
    }

    /**
     * Get user settings
     */
    public function index()
    {
        $user = Auth::user();
        $settings = $this->settingsService->getUserSettings($user);

        return response()->json($settings);
    }

    /**
     * Update account information
     */
    public function updateAccount(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $updatedUser = $this->settingsService->updateAccount($user, $request->only(['name', 'email']));

        return response()->json([
            'message' => 'Konta informācija atjaunināta veiksmīgi',
            'user' => $updatedUser
        ]);
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $success = $this->settingsService->updatePassword(
            $user, 
            $request->current_password, 
            $request->password
        );

        if (!$success) {
            return response()->json([
                'message' => 'Pašreizējā parole ir nepareiza'
            ], 422);
        }

        return response()->json([
            'message' => 'Parole atjaunināta veiksmīgi'
        ]);
    }

    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request)
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'test_reminders' => 'boolean',
            'weekly_progress_emails' => 'boolean',
            'achievement_notifications' => 'boolean',
        ]);

        $user = Auth::user();
        $this->settingsService->updateNotificationSettings($user, $request->all());

        return response()->json([
            'message' => 'Paziņojumu iestatījumi atjaunināti veiksmīgi',
            'notification_settings' => $request->only([
                'email_notifications', 
                'test_reminders', 
                'weekly_progress_emails', 
                'achievement_notifications'
            ])
        ]);
    }

    /**
     * Delete user account
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirmation' => 'required|string|in:DZĒST'
        ]);

        $user = Auth::user();
        $success = $this->settingsService->deleteAccount($user);

        if (!$success) {
            return response()->json([
                'message' => 'Neizdevās dzēst kontu. Lūdzu, mēģiniet vēlāk.'
            ], 500);
        }

        return response()->json([
            'message' => 'Konts dzēsts veiksmīgi'
        ]);
    }

    /**
     * Export user data
     */
    public function exportData()
    {
        $user = Auth::user();
        $userData = $this->settingsService->exportUserData($user);

        return response()->json($userData);
    }

    /**
     * Update user preferences
     */
    public function updatePreferences(Request $request)
    {
        $request->validate([
            'language' => 'string|in:lv,en,ru',
            'timezone' => 'string',
            'custom_preferences' => 'array'
        ]);

        $user = Auth::user();
        $this->settingsService->updatePreferences($user, $request->all());

        return response()->json([
            'message' => 'Preferences atjaunināti veiksmīgi',
            'preferences' => $request->all()
        ]);
    }

    /**
     * Clear user cache
     */
    public function clearCache()
    {
        $user = Auth::user();
        $success = $this->settingsService->clearUserCache($user);

        if (!$success) {
            return response()->json([
                'message' => 'Neizdevās notīrīt kešatmiņu'
            ], 500);
        }

        return response()->json([
            'message' => 'Kešatmiņa notīrīta veiksmīgi'
        ]);
    }

    /**
     * Get available languages
     */
    public function getLanguages()
    {
        $languages = $this->settingsService->getAvailableLanguages();

        return response()->json($languages);
    }

    /**
     * Get available timezones
     */
    public function getTimezones()
    {
        $timezones = $this->settingsService->getAvailableTimezones();

        return response()->json($timezones);
    }
}
