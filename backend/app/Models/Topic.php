<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'prerequisite_level',
        'next_topic_ids',
    ];

    protected $casts = [
        'next_topic_ids' => 'array',
        'prerequisite_level' => 'integer',
    ];

    /**
     * Tests associated with this topic.
     */
    public function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }

    /**
     * User progress records for this topic.
     */
    public function userProgress(): HasMany
    {
        return $this->hasMany(UserTopicProgress::class);
    }

    /**
     * Questions associated with this topic.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}


