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
                'user_id' => 2,
                'name' => 'Book 1',
                'publishing_house' => 'Питер',
                'publication_year' => '2020',
                'isbn' => '123456789',
                'page_count' => 300,
            ],
            [
                'user_id' => 2,
                'name' => 'Book 2',
                'publishing_house' => 'ЕЕЕ',
                'publication_year' => '2021',
                'isbn' => '12345678',
                'page_count' => 300,
            ],
            [
                'user_id' => 3,
                'name' => 'Book 3',
                'publishing_house' => 'фывфыв',
                'publication_year' => '2022',
                'isbn' => '1234567',
                'page_count' => 300,
            ],
        ];
        $id = 1;
        foreach ($books as $bookData) {
            $book = new Book($bookData);
            $type = BookType::find($id);
            $book->type()->associate($type);
            $book->save();
            $book->authors()->attach($id);
            $book->genres()->attach($id++);
        }
    }
}