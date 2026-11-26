<?php

namespace App\Http\Controllers;

use App\Http\Requests\Game\SubmitGameMoveRequest;
use App\Http\Requests\Game\StoreGameRequest;
use App\Models\Game;
use App\Services\Game\CreateGameService;
use App\Services\Game\PlayGameService;
use App\Services\Game\SubmitGameMoveService;
use App\Services\Game\PauseGameService;
use Illuminate\Http\JsonResponse;

class GameController extends Controller
{
    public function create()
    {
        return view('game.create');
    }

    public function store(
        StoreGameRequest $request,
        CreateGameService $createGameService
    ) {
        $validated = $request->validated();

        $game = $createGameService->execute(
            $request->user()->id,
            $validated['difficulty']
        );

        return redirect()->route('game.play', $game->id);
    }

    public function play(
        Game $game,
        PlayGameService $playGameService
    ) {
        $this->authorize('view', $game);

        $canPlay = $playGameService->execute($game);

        if (!$canPlay) {
            return redirect()->route('menu');
        }

        $game->load('gameCards');

        return view('game.play', compact('game'));
    }

    public function pause(
        Game $game,
        PauseGameService $pauseGameService
    ) {
        $this->authorize('view', $game);

        $pauseGameService->execute($game);

        return redirect()->route('menu');
    }

    public function submitMove(
        SubmitGameMoveRequest $request,
        Game $game,
        SubmitGameMoveService $submitGameMoveService
    ): JsonResponse {
        $this->authorize('view', $game);

        $validated = $request->validated();

        $result = $submitGameMoveService->execute(
            $game,
            $validated['cards']
        );

        return response()->json($result);
    }
}
