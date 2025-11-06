<?php

namespace Database\Seeders;

use App\Models\Chat;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    public function run()
    {
        $chats = [
            [
                'first_user_id' => 1,
                'second_user_id' => 2,
            ],
            [
                'first_user_id' => 2,
                'second_user_id' => 3,
            ],
            [
                'first_user_id' => 3,
                'second_user_id' => 4,
            ],
        ];

        collect($chats)->each(function ($chat) {
            Chat::create($chat);
        });
    }
}
