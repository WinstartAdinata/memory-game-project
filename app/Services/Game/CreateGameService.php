<?php

namespace App\Services\Game;

use App\Models\Game;
use App\Models\Card;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateGameService
{
    public function execute(int $userId, string $difficulty): Game
    {
        $groupCount = $difficulty === 'easy' ? 4 : 5;
        $cardsPerGroup = $difficulty === 'easy' ? 2 : 3;

        return DB::transaction(function () use ($userId, $difficulty, $groupCount, $cardsPerGroup) {
            Game::where('user_id', $userId)
                ->where('status', ['playing', 'paused'])
                ->update([
                    'status' => 'unfinished',
                ]);

            $game = Game::create([
                'user_id' => $userId,
                'difficulty' => $difficulty,
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

            $position = 1;

            foreach ($cards as $card) {
                $game->gameCards()->create([
                    ...$card,
                    'position' => $position++,
                ]);
            }

            return $game;
        });
    }
}
