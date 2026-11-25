<?php

namespace App\Http\Controllers;

use App\Http\Requests\Game\SubmitGameMoveRequest;
use App\Models\Game;
use App\Models\Card;
use App\Http\Requests\Game\StoreGameRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
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

            Game::where('user_id', $request->user()->id)
                ->where('status', 'playing')
                ->update([
                    'status' => 'unfinished',
                ]);

            $game = Game::create([
                'user_id' => $request->user()->id,
                'difficulty' => $validated['difficulty'],
                'status' => 'playing',
                'score' => 0,
                'moves' => 0,
                'elapsed_seconds' => 0,
                'started_at' => now(),
                'last_resumed_at' => now(),
            ]);

            $selectedCards = Card::query()
                ->inRandomOrder()
                ->limit($groupCount)
                ->get();

            $position = 1;

            $cards = [];

            foreach ($selectedCards as $card) {
                $groupKey = (string) Str::uuid();

                for ($i = 0; $i < $cardsPerGroup; $i++) {
                    $cards[] = [
                        'card_id' => $card->id,
                        'group_key' => $groupKey,
                        'image_url' => $card->image_url,
                        'is_matched' => false,
                    ];
                }
            }

            $cards = collect($cards)->shuffle();

            foreach ($cards as $card) {
                $game->gameCards()->create([
                    ...$card,
                    'position' => $position++,
                ]);
            }

            return $game;
        });

        return redirect()->route('game.play', $game->id);
    }


    public function play(Game $game)
    {
        $this->authorize('view', $game);

        if (
            $game->status !== 'playing' &&
            $game->status !== 'paused'
        ) {
            return redirect()->route('menu');
        }

        if ($game->status === 'paused') {
            $game->status = 'playing';
            $game->last_resumed_at = now();

            $game->save();
        }

        $game->load('gameCards');

        return view('game.play', compact('game'));
    }

    public function submitMove(
        SubmitGameMoveRequest $request,
        Game $game
    ): JsonResponse {
        $this->authorize('view', $game);

        $validated = $request->validated();

        $result = DB::transaction(function () use ($game, $validated) {

            $game = Game::query()
                ->whereKey($game->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($game->status !== 'playing') {
                abort(422, 'This game is no longer active.');
            }

            $expectedCards = $game->difficulty === 'easy' ? 2 : 3;

            if (count($validated['cards']) !== $expectedCards) {
                abort(422, 'Invalid number of selected cards.');
            }

            $cards = $game->gameCards()
                ->whereIn('id', $validated['cards'])
                ->lockForUpdate()
                ->get();

            if ($cards->count() !== $expectedCards) {
                abort(422, 'Invalid card selection.');
            }

            if ($cards->contains('is_matched', true)) {
                abort(422, 'A matched card cannot be selected again.');
            }

            $isMatch = $cards->pluck('group_key')->unique()->count() === 1;

            $pointsChanged = $isMatch ? 2 : -1;

            if ($isMatch) {
                $game->gameCards()
                    ->whereIn('id', $validated['cards'])
                    ->update([
                        'is_matched' => true,
                    ]);
            }

            $game->score += $pointsChanged;
            $game->moves += 1;

            $isCompleted = $game->gameCards()
                ->where('is_matched', false)
                ->doesntExist();

            if ($isCompleted) {
                $game->elapsed_seconds += now()->diffInSeconds(
                    $game->last_resumed_at
                );

                $game->last_resumed_at = null;
                $game->status = 'completed';
                $game->completed_at = now();
            }

            $game->save();

            $game->logs()->create([
                'cards' => $cards->pluck('id')->values()->all(),
                'points_changed' => $pointsChanged,
                'action_type' => $isMatch ? 'match' : 'miss',
                'score_after' => $game->score,
                'move_number' => $game->moves,
            ]);

            return [
                'matched' => $isMatch,
                'score' => $game->score,
                'moves' => $game->moves,
                'completed' => $isCompleted,
            ];
        });

        return response()->json($result);
    }

    public function pause(Game $game)
    {
        $this->authorize('view', $game);

        if ($game->status !== 'playing') {
            return redirect()->route('menu');
        }

        $additionalSeconds = now()->diffInSeconds(
            $game->last_resumed_at
        );

        $game->elapsed_seconds += $additionalSeconds;

        $game->last_resumed_at = null;
        $game->status = 'paused';

        $game->save();

        return redirect()->route('menu');
    }
}
