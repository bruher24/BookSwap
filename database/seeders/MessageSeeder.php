<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Seeder;

final class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $messages = [
            [
                'chat_id' => 1,
                'from_id' => 2,
                'to_id' => 1,
                'subject' => '123',
                'body' => '1 Test Body',
            ],
            [

                'chat_id' => 1,
                'from_id' => 1,
                'to_id' => 2,
                'subject' => '123',
                'body' => '2 Test Body',
            ],
            [

                'chat_id' => 1,
                'from_id' => 2,
                'to_id' => 1,
                'subject' => '123',
                'body' => '3 Test Body',
            ],
            [

                'chat_id' => 1,
                'from_id' => 1,
                'to_id' => 2,
                'subject' => '123',
                'body' => '4 Test Body',
            ],
        ];
        collect($messages)->each(function ($message) {
            Message::create($message);
        });
    }
}
