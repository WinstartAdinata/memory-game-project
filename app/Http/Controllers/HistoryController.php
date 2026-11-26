<?php

namespace App\Http\Controllers;

use App\Services\GetHistoryListService;

class HistoryController extends Controller
{
    public function index(GetHistoryListService $getHistoryListService)
    {
        $games = $getHistoryListService->execute(auth()->id());
        return view('history', compact('games'));
    }
}
