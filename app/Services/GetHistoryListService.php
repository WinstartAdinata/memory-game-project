<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Log;

class GetHistoryListService
{
    public function execute($userId)
    {
        $completedGames = $this->getCompletedGames($userId);
        $unfinishedGames = $this->getUnfinishedGames($userId);
        $totalTime = $this->getTotalTime($userId);
        $games = $this->getHistoryGames($userId);

        $totalSuccess = $this->getTotalSuccess($userId);
        $totalAttempts = $this->getTotalAttempts($userId);
        $accuracy = $this->calculateAccuracy($totalSuccess, $totalAttempts);

        return compact(
            'games',
            'completedGames',
            'unfinishedGames',
            'totalTime',
            'accuracy'
        );
    }

    private function getCompletedGames($userId)
    {
        return Game::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();
    }

    private function getUnfinishedGames($userId)
    {
        return Game::where('user_id', $userId)
            ->where('status', 'unfinished')
            ->count();
    }

    private function getTotalTime($userId)
    {
        return Game::where('user_id', $userId)
            ->sum('elapsed_seconds');
    }

    private function getHistoryGames($userId)
    {
        return Game::where('user_id', $userId)
            ->whereIn('status', ['completed', 'unfinished'])
            ->latest()
            ->paginate(10);
    }

    private function getTotalSuccess($userId)
    {
        return Log::whereHas('game', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->whereIn('status', ['completed', 'unfinished']);
        })
            ->where('action_type', 'match')
            ->count();
    }

    private function getTotalAttempts($userId)
    {
        return Log::whereHas('game', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->whereIn('status', ['completed', 'unfinished']);
        })
            ->whereIn('action_type', ['match', 'miss'])
            ->count();
    }

    private function calculateAccuracy($totalSuccess, $totalAttempts)
    {
        return $totalAttempts > 0
            ? round(($totalSuccess / $totalAttempts) * 100, 1)
            : 0;
    }
}
