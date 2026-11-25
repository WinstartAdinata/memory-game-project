<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Services\GetHistoryListService;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(GetHistoryListService $getHistoryListService)
    {
        $games = $getHistoryListService->getHistoryList(auth()->id());
        return view('history', compact('games'));
    }
}
