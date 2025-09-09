<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Topic;
use App\Services\SelfTestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SelfTestController extends Controller
{
    public function __construct(private SelfTestService $service) {}

    public function start(Request $request)
    {
        $data = $request->validate([
            'topic_id' => ['required', 'integer', 'exists:topics,id'],
            'question_count' => ['nullable', 'integer', 'min:1', 'max:50'],
            'grade' => ['nullable', 'integer', 'min:1', 'max:12'],
        ], [
            'topic_id.required' => 'Tēmas ID ir obligāts',
            'topic_id.integer' => 'Tēmas ID jābūt skaitlim',
            'topic_id.exists' => 'Tēma nav atrasta',
            'question_count.integer' => 'Jautājumu skaitam jābūt skaitlim',
            'question_count.min' => 'Jautājumu skaitam jābūt vismaz 1',
            'question_count.max' => 'Jautājumu skaits nedrīkst pārsniegt 50',
            'grade.integer' => 'Klasei jābūt skaitlim',
            'grade.min' => 'Klasei jābūt vismaz 1',
            'grade.max' => 'Klasei jābūt ne vairāk par 12',
        ]);

        $user = Auth::user();
        $topic = Topic::findOrFail($data['topic_id']);
        $questionCount = $data['question_count'] ?? 10;
        
        $test = $this->service->startSelfTest($user, $topic, $questionCount, $data['grade'] ?? null);

        return response()->json($test, 201);
    }

    public function getQuestions(Test $test)
    {
        $this->authorize('view', $test);
        
        // Reuse the same method from FinalTestService
        $finalTestService = app(\App\Services\FinalTestService::class);
        $questions = $finalTestService->getTestQuestions($test);
        
        return response()->json($questions);
    }

    public function submit(Request $request, Test $test)
    {
        $this->authorize('update', $test);

        $data = $request->validate([
            'answers' => ['required', 'array'],
            'answers.*' => ['required', 'integer', 'exists:answers,id'],
        ], [
            'answers.required' => 'Atbildes ir obligātas',
            'answers.array' => 'Atbildēm jābūt masīvam',
            'answers.*.required' => 'Katrai atbildei jābūt norādītai',
            'answers.*.integer' => 'Atbildes ID jābūt skaitlim',
            'answers.*.exists' => 'Atbilde nav atrasta',
        ]);

        $result = $this->service->submitSelfTest($test, $data['answers']);

        return response()->json($result);
    }
}