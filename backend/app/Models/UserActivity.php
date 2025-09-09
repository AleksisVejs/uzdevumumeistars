<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'xp_earned',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function createActivity(int $userId, string $type, string $description, int $xpEarned = 0, array $metadata = []): self
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'description' => $description,
            'xp_earned' => $xpEarned,
            'metadata' => $metadata
        ]);
    }
}