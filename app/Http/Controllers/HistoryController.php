<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        $games = Game::where('user_id', auth()->id())
            ->whereIn('status', ['completed', 'unfinished'])
            ->latest()
            ->paginate(5);
        return view('history', compact('games'));
    }
}
