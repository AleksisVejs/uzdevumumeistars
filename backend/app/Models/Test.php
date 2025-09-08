<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'topic_id',
        'type',
        'total_questions',
        'time_limit_seconds',
        'score_correct',
        'score_total',
        'passed',
        'started_at',
        'submitted_at',
        'metadata',
    ];

    protected $casts = [
        'passed' => 'boolean',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function testQuestions(): HasMany
    {
        return $this->hasMany(TestQuestion::class);
    }

    public function questions(): HasMany
    {
        return $this->hasManyThrough(Question::class, TestQuestion::class, 'test_id', 'id', 'id', 'question_id');
    }
}


