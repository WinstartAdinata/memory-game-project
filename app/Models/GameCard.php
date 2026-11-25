<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameCard extends Model
{
    protected $fillable = [
        'game_id',
        'card_id',
        'group_key',
        'image_url',
        'position',
        'is_matched',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'is_matched' => 'boolean',
        ];
    }
}
