<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;

final class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $notifications = [
            [
                'subject' => 'test1',
                'body' => 'lorem',
                'user_id' => 1,
                'seen' => false,
            ],
            [
                'subject' => 'test1',
                'body' => 'ipsum',
                'user_id' => 1,
                'seen' => false,
            ],
            [
                'subject' => 'test2',
                'body' => 'asdasd',
                'user_id' => 1,
                'seen' => false,
            ],
            [
                'subject' => 'test3',
                'body' => 'poqwpeoqwe',
                'user_id' => 1,
                'seen' => true,
            ],
        ];
        collect($notifications)->each(function ($notification) {
            Notification::create($notification);
        });
    }
}
