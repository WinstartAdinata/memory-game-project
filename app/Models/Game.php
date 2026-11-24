<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    protected $fillable = [
        'user_id',
        'difficulty',
        'status',
        'score',
        'moves',
        'elapsed_seconds',
        'started_at',
        'paused_at',
        'completed_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gameCards(): HasMany
    {
        return $this->hasMany(GameCard::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }
}
