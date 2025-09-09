
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinalTestController;
use App\Http\Controllers\SelfTestController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserProgressController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) { return $request->user(); });
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/topics/unlocked', [TopicController::class, 'unlocked']);
    Route::get('/topics/all', [TopicController::class, 'all']);
    Route::get('/topics/{topic}', [TopicController::class, 'show']);
    Route::get('/topics/{topic}/has-questions/{grade}', [TopicController::class, 'hasQuestionsForGrade']);
    Route::get('/topics/{topic}/practice-tests-status', [TopicController::class, 'practiceTestsStatus']);

    Route::post('/tests/final', [FinalTestController::class, 'start']);
    Route::get('/tests/{test}', [FinalTestController::class, 'show']);
    Route::get('/tests/{test}/questions', [FinalTestController::class, 'getQuestions']);
    Route::post('/tests/{test}/final/submit', [FinalTestController::class, 'submit']);

    Route::post('/tests/self', [SelfTestController::class, 'start']);
    Route::post('/tests/{test}/self/submit', [SelfTestController::class, 'submit']);

    Route::get('/user/progress/topics', [UserProgressController::class, 'index']);

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::put('/profile', [ProfileController::class, 'update']);

    // Settings routes
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::put('/settings/account', [SettingsController::class, 'updateAccount']);
    Route::put('/settings/password', [SettingsController::class, 'updatePassword']);
    Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications']);
    Route::put('/settings/preferences', [SettingsController::class, 'updatePreferences']);
    Route::delete('/settings/account', [SettingsController::class, 'deleteAccount']);
    Route::get('/settings/export', [SettingsController::class, 'exportData']);
    Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache']);
    Route::get('/settings/languages', [SettingsController::class, 'getLanguages']);
    Route::get('/settings/timezones', [SettingsController::class, 'getTimezones']);
});


