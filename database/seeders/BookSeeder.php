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
                'user_id' => 1,
                'name' => 'Тестовая книга',
                'publishing_house' => 'Питер',
                'publication_year' => '2020',
                'isbn' => '123456789',
                'page_count' => 300,
            ],
            [
                'user_id' => 1,
                'name' => 'Онегин',
                'publishing_house' => 'ЕЕЕ',
                'publication_year' => '2021',
                'isbn' => '12345678',
                'page_count' => 400,
            ],
            [
                'user_id' => 1,
                'name' => 'Зов Ктулху',
                'publishing_house' => 'фывфыв',
                'publication_year' => '2022',
                'isbn' => '1234567',
                'page_count' => 666,
            ],
            [
                'user_id' => 1,
                'name' => 'ГОСТ 2281337',
                'publishing_house' => 'минобр',
                'publication_year' => '1978',
                'isbn' => '00000001',
                'page_count' => 3,
            ],
        ];
        $id = 1;
        foreach ($books as $bookData) {
            $book = new Book($bookData);
            $type = BookType::find($id);
            $book->book_type()->associate($type);
            $book->save();
            $book->authors()->attach($id);
            $book->genres()->attach($id);
            $book->genres()->attach(++$id);
        }
    }
}