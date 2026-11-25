<?php

namespace App\Services;

use App\Models\Game;

class GetHistoryListService
{
    public function getHistoryList($userId)
    {
        return Game::where($userId)
            ->whereIn('status', ['completed', 'unfinished'])
            ->latest()
            ->paginate(5);
    }
}
