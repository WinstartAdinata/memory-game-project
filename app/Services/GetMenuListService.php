<?php

namespace App\Services;

use App\Models\Game;

class GetMenuListService
{
    public function getMenuList($userId)
    {
        return Game::where('user_id', $userId)
            ->where('status', 'paused')
            ->latest()
            ->first();
    }
}
