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
                'name' => 'Genre 1',
            ],
            [
                'name' => 'Genre 2',

            ],
            [
                'name' => 'Genre 3',

            ],
        ];
        collect($genres)->each(function ($genre) {
            Genre::create($genre);
        });
    }
}