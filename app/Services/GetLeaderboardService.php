<?php

namespace App\Services\Leaderboard;

use App\Models\Game;

class GetLeaderboardService
{
    public function execute(): array
    {
        return [
            'easyScores' => $this->getTopScores('easy'),
            'easyTimes' => $this->getTopTimes('easy'),
            'hardScores' => $this->getTopScores('hard'),
            'hardTimes' => $this->getTopTimes('hard'),
        ];
    }

    private function getTopScores(string $difficulty)
    {
        return Game::with('user')
            ->selectRaw('user_id, MAX(score) as score')
            ->where('difficulty', $difficulty)
            ->where('status', 'completed')
            ->groupBy('user_id')
            ->orderByDesc('score')
            ->limit(5)
            ->get();
    }

    private function getTopTimes(string $difficulty)
    {
        return Game::with('user')
            ->selectRaw('user_id, MIN(elapsed_seconds) as elapsed_seconds')
            ->where('difficulty', $difficulty)
            ->where('status', 'completed')
            ->groupBy('user_id')
            ->orderBy('elapsed_seconds')
            ->limit(5)
            ->get();
    }
}
