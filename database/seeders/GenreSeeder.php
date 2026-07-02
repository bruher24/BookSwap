<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

final class GenreSeeder extends Seeder
{
    public function run(): void
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
        collect($genres)->each(function (array $genre): void {
            Genre::query()->updateOrCreate(['name' => $genre['name']], $genre);
        });
    }
}
