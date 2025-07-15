<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Cover;
use Illuminate\Database\Seeder;

class CoverSeeder extends Seeder
{
    public function run()
    {
        $covers = [
            [
                'src' => 'covers/cover.png'
            ],
        ];

        collect($covers)->each(function ($coverData) {
            $cover = new Cover($coverData);
            $cover->book()->associate(Book::first()->id);
            $cover->save();
        });
    }
}