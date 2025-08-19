<?php

namespace Database\Seeders;

use App\Models\BookType;
use Illuminate\Database\Seeder;

class BookTypeSeeder extends Seeder
{
    public function run(): void
    {
        $bookTypes = [
            [
                'name' => 'Твердая обложка',
            ],
            [
                'name' => 'Мягкая обложка',
            ],
            [
                'name' => 'Электронная',
            ],
            [
                'name' => 'Другое',
            ],
        ];
        collect($bookTypes)->each(function ($bookType) {
            BookType::create($bookType);
        });
    }
}
