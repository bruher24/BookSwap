<?php

namespace Database\Seeders;

use App\Models\Chat;
use Illuminate\Database\Seeder;

final class ChatSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 2; $i++) {
            $pairKey = $i . ':' . $i + 1;
            $chat = Chat::create(['pair_key' => $pairKey]);
            $chat->users()->attach([$i, $i + 1]);
        }
    }
}
