<?php

namespace App\Services\Game;

use App\Models\Game;

class PauseGameService
{
    public function execute(Game $game): bool
    {
        if ($game->status !== 'playing') {
            return false;
        }

        $additionalSeconds = max(
            0,
            now()->timestamp - $game->last_resumed_at->timestamp
        );

        $game->elapsed_seconds += $additionalSeconds;

        $game->last_resumed_at = null;
        $game->status = 'paused';

        $game->save();

        return true;
    }
}
