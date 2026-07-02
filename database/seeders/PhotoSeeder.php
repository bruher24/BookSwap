<?php

namespace Database\Seeders;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Database\Seeder;

final class PhotoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@admin.com')->firstOrFail();

        Photo::query()->updateOrCreate(
            ['src' => 'avatars/avatar.png'],
            ['user_id' => $admin->id]
        );
    }
}
