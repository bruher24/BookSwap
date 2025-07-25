<?php

namespace Database\Seeders;

use App\Models\Chat;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    public function run()
    {
        $chat = [
            'first_user_id' => 1,
            'second_user_id' => 2,
        ];
        Chat::create($chat);
    }
}