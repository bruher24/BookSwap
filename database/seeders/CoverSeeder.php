<?php

namespace Database\Seeders;

use App\Models\Cover;
use App\Models\User;
use Illuminate\Database\Seeder;

final class CoverSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@admin.com')->firstOrFail();

        Cover::query()->updateOrCreate(
            ['src' => 'covers/cover.png'],
            ['user_id' => $admin->id]
        );
    }
}
