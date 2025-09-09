<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Topic;
use App\Services\FinalTestService;
use App\Services\SelfTestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinalTestController extends Controller
{
    public function __construct(private FinalTestService $service) {}

    public function start(Request $request)
    {
        $data = $request->validate([
            'topic_id' => ['required', 'integer', 'exists:topics,id'],
            'question_count' => ['required', 'integer', 'min:1', 'max:100'],
            'time_limit_seconds' => ['nullable', 'integer', 'min:30', 'max:7200'],
            'grade' => ['nullable', 'integer', 'min:1', 'max:12'],
        ]);

        $user = Auth::user();
        $topic = Topic::findOrFail($data['topic_id']);
        $test = $this->service->startFinalTest(
            $user, 
            $topic, 
            $data['question_count'], 
            $data['time_limit_seconds'] ?? null,
            $data['grade'] ?? null
        );

        return response()->json($test, 201);
    }

    public function show(Test $test)
    {
        $this->authorize('view', $test);
        
        return response()->json($test);
    }

    public function getQuestions(Test $test)
    {
        $this->authorize('view', $test);
        
        // Both final tests and self tests use the same getTestQuestions method
        $questions = $this->service->getTestQuestions($test);
        
        return response()->json($questions);
    }

    public function submit(Request $request, Test $test)
    {
        $this->authorize('update', $test);

        $data = $request->validate([
            'answers' => ['required', 'array'],
            'answers.*' => ['required', 'integer', 'exists:answers,id'],
        ]);

        $updated = $this->service->submitFinalTest($test, $data['answers']);

        return response()->json($updated);
    }
}


