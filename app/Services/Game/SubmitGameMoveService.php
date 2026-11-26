<?php

namespace App\Services\Game;

use App\Models\Game;
use Illuminate\Support\Facades\DB;

class SubmitGameMoveService
{
    public function execute(Game $game, array $cardIds): array
    {
        return DB::transaction(function () use ($game, $cardIds) {

            $game = Game::query()
                ->whereKey($game->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($game->status !== 'playing') {
                abort(422, 'This game is no longer active.');
            }

            $expectedCards = $game->difficulty === 'easy' ? 2 : 3;

            if (count($cardIds) !== $expectedCards) {
                abort(422, 'Invalid number of selected cards.');
            }

            $cards = $game->gameCards()
                ->whereIn('id', $cardIds)
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
                    ->whereIn('id', $cardIds)
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
                $additionalSeconds = max(
                    0,
                    now()->timestamp - $game->last_resumed_at->timestamp
                );

                $game->elapsed_seconds += (int) $additionalSeconds;

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
    }
}
