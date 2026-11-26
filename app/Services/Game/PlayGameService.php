<?php

namespace App\Services\Game;

use App\Models\Game;
use Illuminate\Support\Carbon;

class PlayGameService
{
    public function execute(Game $game): bool
{
    if ($game->status === 'paused') {
        $game->status = 'playing';
        $game->last_resumed_at = Carbon::now();
        $game->save();

        return true;
    }

    return $game->status === 'playing';
}
}
