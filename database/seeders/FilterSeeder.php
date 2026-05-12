<?php

namespace Database\Seeders;

use App\Models\Filter;
use Illuminate\Database\Seeder;

final class FilterSeeder extends Seeder
{
    public function run(): void
    {
        $filters = [
            [
                'name' => 'Автор',
                'by_fields' => 'author_id',
            ],
            [
                'name' => 'Жанр',
                'by_fields' => 'genre_id',
            ],
            [
                'name' => 'Год издания',
                'by_fields' => 'publication_year',
            ],
            [
                'name' => 'Тип',
                'by_fields' => 'book_type_id',
            ],
            [
                'name' => 'Название',
                'by_fields' => 'name',
            ]
        ];

        collect($filters)->each(function ($filter) {
            Filter::create($filter);
        });
    }
}
