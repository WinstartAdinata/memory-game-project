<?php

namespace App\Http\Controllers;

use App\Services\GetMenuListService;

class MenuController extends Controller
{
    public function index(GetMenuListService $getMenuListService)
    {
        $continueGame = $getMenuListService->execute(auth()->id());

        return view('menu', compact('continueGame'));
    }
}
