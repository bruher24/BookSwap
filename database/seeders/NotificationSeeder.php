<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

final class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@admin.com')->firstOrFail();

        $notifications = [
            [
                'subject' => 'test1',
                'body' => 'lorem',
                'user_id' => $admin->id,
                'seen' => false,
            ],
            [
                'subject' => 'test1',
                'body' => 'ipsum',
                'user_id' => $admin->id,
                'seen' => false,
            ],
            [
                'subject' => 'test2',
                'body' => 'asdasd',
                'user_id' => $admin->id,
                'seen' => false,
            ],
            [
                'subject' => 'test3',
                'body' => 'poqwpeoqwe',
                'user_id' => $admin->id,
                'seen' => true,
            ],
        ];
        collect($notifications)->each(function (array $notification): void {
            Notification::query()->firstOrCreate($notification);
        });
    }
}
