<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    protected $fillable = [
        'game_id',
        'cards',
        'points_changed',
        'action_type',
        'score_after',
        'move_number',
    ];

    protected function casts(): array
    {
        return [
            'cards' => 'array',
            'points_changed' => 'integer',
            'score_after' => 'integer',
            'move_number' => 'integer',
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
