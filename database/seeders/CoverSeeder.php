<?php

namespace Database\Seeders;

use App\Models\Cover;
use Illuminate\Database\Seeder;

final class CoverSeeder extends Seeder
{
    public function run(): void
    {
        $covers = [
            [
                'src' => 'covers/cover.png',
                'user_id' => 1,
            ],
        ];

        collect($covers)->each(function ($cover) {
            Cover::create($cover);
        });
    }
}
