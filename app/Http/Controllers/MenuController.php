<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Services\GetMenuListService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(GetMenuListService $getMenuListService)
    {
        $continueGame = $getMenuListService->getMenuList(auth()->id());

        return view('menu', compact('continueGame'));
    }
}
