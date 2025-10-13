<?php

namespace Database\Seeders;

use App\Models\Cover;
use Illuminate\Database\Seeder;

class CoverSeeder extends Seeder
{
    public function run(): void
    {
        $covers = [
            [
                'src' => 'covers/cover.png'
            ],
        ];

        collect($covers)->each(function ($cover) {
            Cover::create($cover);
        });
    }
}
