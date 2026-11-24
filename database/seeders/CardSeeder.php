<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cards = [];

        for ($i = 10; $i <= 25; $i++) {
            $cards[] = [
                'image_url' => "https://picsum.photos/seed/memory-{$i}/200/200",
            ];
        }
        Card::insert($cards);
    }
}
