<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Card;
use App\Http\Requests\Game\StoreGameRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function create()
    {
        return view('game.create');
    }


    public function store(StoreGameRequest $request)
    {
        $validated = $request->validated();

        $groupCount = $validated['difficulty'] === 'easy' ? 4 : 5;
        $cardsPerGroup = $validated['difficulty'] === 'easy' ? 2 : 3;

        $game = DB::transaction(function () use ($request, $validated, $groupCount, $cardsPerGroup) {

            $game = Game::create([
                'user_id' => $request->user()->id,
                'difficulty' => $validated['difficulty'],
                'status' => 'playing',
                'score' => 0,
                'moves' => 0,
                'elapsed_seconds' => 0,
                'started_at' => now(),
            ]);

            $selectedCards = Card::query()
                ->inRandomOrder()
                ->limit($groupCount)
                ->get();

            $position = 1;

            foreach ($selectedCards as $card) {
                $groupKey = (string) Str::uuid();

                for ($i = 0; $i < $cardsPerGroup; $i++) {
                    $game->gameCards()->create([
                        'card_id' => $card->id,
                        'group_key' => $groupKey,
                        'image_url' => $card->image_url,
                        'position' => $position++,
                        'is_matched' => false,
                    ]);
                }
            }

            return $game;
        });

        return redirect()->route('game.play', $game->id);
    }


    public function play(Game $game)
    {
        $this->authorize('view', $game);

        $game->load('gameCards');

        return view('game.play', compact('game'));
    }
}
