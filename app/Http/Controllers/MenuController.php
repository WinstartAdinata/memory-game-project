<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $continueGame = Game::where('user_id', auth()->id())
            ->where('status', 'paused')
            ->latest()
            ->first();
        return view('menu', compact('continueGame' ?? null));
    }
}
