<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookType;

class BookTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => 'Твердая обложка'],
            ['name' => 'Мягкая обложка'],
            ['name' => 'Электронная'],
            ['name' => 'Другое'],
        ];
        collect($types)->each(function ($type) {
            BookType::create($type);
        });
    }
}