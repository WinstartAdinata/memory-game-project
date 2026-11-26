<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class GameHistorySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {

            for ($i = 0; $i < 8; $i++) {

                $startedAt = Carbon::now()
                    ->subDays(rand(1, 30))
                    ->subHours(rand(1, 12));

                $elapsedSeconds = rand(30, 300);

                $status = fake()->randomElement([
                    'completed',
                    'unfinished',
                ]);

                Game::create([
                    'user_id' => $user->id,
                    'difficulty' => fake()->randomElement([
                        'easy',
                        'hard',
                    ]),
                    'status' => $status,
                    'score' => rand(-10, 10),
                    'moves' => rand(5, 20),
                    'elapsed_seconds' => $elapsedSeconds,
                    'started_at' => $startedAt,
                    'completed_at' => $status === 'completed'
                        ? $startedAt->copy()->addSeconds($elapsedSeconds)
                        : null,
                    'last_resumed_at' => null,
                    'created_at' => $startedAt,
                    'updated_at' => $startedAt,
                ]);
            }
        }
    }
}
