<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    public function run()
    {
        $genres = [
            [
                'name' => 'Детектив',
            ],
            [
                'name' => 'Фантастика',

            ],
            [
                'name' => 'Учебник',

            ],
        ];
        collect($genres)->each(function ($genre) {
            Genre::create($genre);
        });
    }
}