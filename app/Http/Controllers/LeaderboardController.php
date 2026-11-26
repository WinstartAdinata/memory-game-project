<?php

namespace App\Http\Controllers;

use App\Models\Game;

class LeaderboardController extends Controller
{
    public function index()
    {
        $easyScores = Game::with('user')
            ->selectRaw('user_id, MAX(score) as score')
            ->where('difficulty', 'easy')
            ->where('status', 'completed')
            ->groupBy('user_id')
            ->orderByDesc('score')
            ->limit(5)
            ->get();

        $easyTimes = Game::with('user')
            ->selectRaw('user_id, MIN(elapsed_seconds) as elapsed_seconds')
            ->where('difficulty', 'easy')
            ->where('status', 'completed')
            ->groupBy('user_id')
            ->orderBy('elapsed_seconds')
            ->limit(5)
            ->get();

        $hardScores = Game::with('user')
            ->selectRaw('user_id, MAX(score) as score')
            ->where('difficulty', 'hard')
            ->where('status', 'completed')
            ->groupBy('user_id')
            ->orderByDesc('score')
            ->limit(5)
            ->get();

        $hardTimes = Game::with('user')
            ->selectRaw('user_id, MIN(elapsed_seconds) as elapsed_seconds')
            ->where('difficulty', 'hard')
            ->where('status', 'completed')
            ->groupBy('user_id')
            ->orderBy('elapsed_seconds')
            ->limit(5)
            ->get();

        return view('leaderboard', compact(
            'easyScores',
            'easyTimes',
            'hardScores',
            'hardTimes'
        ));
    }
}
