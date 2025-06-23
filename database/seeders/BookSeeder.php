<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookType;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run()
    {
        $books = [
            [
                'name' => 'Book 1',
                'publishing_house' => 'Питер',
                'publication_year' => '2020',
                'isbn' => '123456789',
                'page_count' => 300,
                'type_id' => 1,
            ],
            [
                'name' => 'Book 2',
                'publishing_house' => 'ЕЕЕ',
                'publication_year' => '2020',
                'isbn' => '123456789',
                'page_count' => 300,
                'type_id' => 2,
            ],
            [
                'name' => 'Book 3',
                'publishing_house' => 'фывфыв',
                'publication_year' => '2020',
                'isbn' => '123456789',
                'page_count' => 300,
                'type_id' => 3,
            ],
        ];
        collect($books)->each(function ($book) {
            $book = Book::create($book);
            $book->authors()->attach([1,2]);
            $book->genres()->attach([1,3]);
            $type = BookType::find($book->type_id);
            $book->type()->associate($type);
        });
    }
}