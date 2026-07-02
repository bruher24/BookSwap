<?php

namespace Database\Seeders;

use App\Models\BookType;
use Illuminate\Database\Seeder;

final class BookTypeSeeder extends Seeder
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
        collect($bookTypes)->each(function (array $bookType): void {
            BookType::query()->updateOrCreate(['name' => $bookType['name']], $bookType);
        });
    }
}
