<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Seeder;

final class ChatSeeder extends Seeder
{
    public function run(): void
    {
        $pairs = [
            ['seller@seller.com', 'buyer@buyer.com'],
            ['admin@admin.com', 'seller@seller.com'],
        ];

        foreach ($pairs as [$firstEmail, $secondEmail]) {
            $firstUser = User::query()->where('email', $firstEmail)->firstOrFail();
            $secondUser = User::query()->where('email', $secondEmail)->firstOrFail();
            $firstId = min($firstUser->id, $secondUser->id);
            $secondId = max($firstUser->id, $secondUser->id);

            $chat = Chat::query()->firstOrCreate([
                'pair_key' => $firstId . ':' . $secondId,
            ]);

            $chat->users()->syncWithoutDetaching([$firstId, $secondId]);
        }
    }
}
