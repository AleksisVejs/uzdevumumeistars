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
        ], [
            'topic_id.required' => 'Tēmas ID ir obligāts',
            'topic_id.integer' => 'Tēmas ID jābūt skaitlim',
            'topic_id.exists' => 'Tēma nav atrasta',
            'question_count.required' => 'Jautājumu skaits ir obligāts',
            'question_count.integer' => 'Jautājumu skaitam jābūt skaitlim',
            'question_count.min' => 'Jautājumu skaitam jābūt vismaz 1',
            'question_count.max' => 'Jautājumu skaits nedrīkst pārsniegt 100',
            'time_limit_seconds.integer' => 'Laika ierobežojumam jābūt skaitlim',
            'time_limit_seconds.min' => 'Laika ierobežojumam jābūt vismaz 30 sekundes',
            'time_limit_seconds.max' => 'Laika ierobežojums nedrīkst pārsniegt 7200 sekundes',
            'grade.integer' => 'Klasei jābūt skaitlim',
            'grade.min' => 'Klasei jābūt vismaz 1',
            'grade.max' => 'Klasei jābūt ne vairāk par 12',
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
        ], [
            'answers.required' => 'Atbildes ir obligātas',
            'answers.array' => 'Atbildēm jābūt masīvam',
            'answers.*.required' => 'Katrai atbildei jābūt norādītai',
            'answers.*.integer' => 'Atbildes ID jābūt skaitlim',
            'answers.*.exists' => 'Atbilde nav atrasta',
        ]);

        $updated = $this->service->submitFinalTest($test, $data['answers']);

        return response()->json($updated);
    }
}


