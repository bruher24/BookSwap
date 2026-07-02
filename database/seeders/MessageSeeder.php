<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

final class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $seller = User::query()->where('email', 'seller@seller.com')->firstOrFail();
        $buyer = User::query()->where('email', 'buyer@buyer.com')->firstOrFail();
        $firstId = min($seller->id, $buyer->id);
        $secondId = max($seller->id, $buyer->id);
        $chat = Chat::query()->where('pair_key', $firstId . ':' . $secondId)->firstOrFail();

        $messages = [
            [
                'chat_id' => $chat->id,
                'sender_id' => $buyer->id,
                'subject' => '123',
                'body' => '1 Test Body',
            ],
            [

                'chat_id' => $chat->id,
                'sender_id' => $seller->id,
                'subject' => '123',
                'body' => '2 Test Body',
            ],
            [

                'chat_id' => $chat->id,
                'sender_id' => $buyer->id,
                'subject' => '123',
                'body' => '3 Test Body',
            ],
            [

                'chat_id' => $chat->id,
                'sender_id' => $seller->id,
                'subject' => '123',
                'body' => '4 Test Body',
            ],
        ];

        collect($messages)->each(function (array $message): void {
            Message::query()->firstOrCreate($message);
        });
    }
}
