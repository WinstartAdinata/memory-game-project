<?php

namespace App\Http\Controllers;

use App\Services\Leaderboard\GetLeaderboardService;

class LeaderboardController extends Controller
{
    public function index(GetLeaderboardService $leaderboardService)
    {
        $leaderboards = $leaderboardService->execute();

        return view('leaderboard', $leaderboards);
    }
}
