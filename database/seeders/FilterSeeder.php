<?php

namespace Database\Seeders;

use App\Models\Filter;
use Illuminate\Database\Seeder;

class FilterSeeder extends Seeder
{
    public function run(): void
    {
        $filters = [
            [
                'name' => 'Автор',
                'by_fields' => 'author_id',
                'disabled' => false,
            ],
            [
                'name' => 'Жанр',
                'by_fields' => 'genre_id',
                'disabled' => false,
            ],
            [
                'name' => 'Год издания',
                'by_fields' => 'publication_year',
                'disabled' => false,
            ],
            [
                'name' => 'Тип',
                'by_fields' => 'book_type_id',
                'disabled' => false,
            ],
        ];

        collect($filters)->each(function ($filter) {
            Filter::create($filter);
        });
    }
}
