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
            [
                'name' => 'test1',

            ],
            [
                'name' => 'test2',

            ],
            [
                'name' => 'test3',

            ],
            [
                'name' => 'test4',

            ],
            [
                'name' => 'test5',

            ],
        ];
        collect($genres)->each(function ($genre) {
            Genre::create($genre);
        });
    }
}