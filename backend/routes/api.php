
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinalTestController;
use App\Http\Controllers\SelfTestController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserProgressController;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) { return $request->user(); });

    Route::get('/topics/unlocked', [TopicController::class, 'unlocked']);

    Route::post('/tests/final', [FinalTestController::class, 'start']);
    Route::get('/tests/{test}/questions', [FinalTestController::class, 'getQuestions']);
    Route::post('/tests/{test}/final/submit', [FinalTestController::class, 'submit']);

    Route::post('/tests/self', [SelfTestController::class, 'start']);
    Route::post('/tests/{test}/self/submit', [SelfTestController::class, 'submit']);

    Route::get('/user/progress/topics', [UserProgressController::class, 'index']);
});


