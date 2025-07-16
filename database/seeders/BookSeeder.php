<?php

namespace Database\Seeders;

use App\Enums\BookTypeEnum;
use App\Models\Book;
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
                'book_type' => BookTypeEnum::HARD,
                'page_count' => 300,
            ],
            [
                'user_id' => 1,
                'name' => 'Онегин',
                'publishing_house' => 'ЕЕЕ',
                'publication_year' => '2021',
                'isbn' => '12345678',
                'book_type' => BookTypeEnum::SOFT,
                'page_count' => 400,
            ],
            [
                'user_id' => 1,
                'name' => 'Зов Ктулху',
                'publishing_house' => 'фывфыв',
                'publication_year' => '2022',
                'isbn' => '1234567',
                'book_type' => BookTypeEnum::DIGITAL,
                'page_count' => 666,
            ],
            [
                'user_id' => 1,
                'name' => 'ГОСТ 2281337',
                'publishing_house' => 'минобр',
                'publication_year' => '1978',
                'isbn' => '00000001',
                'book_type' => BookTypeEnum::OTHER,
                'page_count' => 3,
            ],
        ];
        $id = 1;
        foreach ($books as $bookData) {
            $book = new Book($bookData);
            $book->save();
            $book->authors()->attach($id);
            $book->genres()->attach($id);
            $book->genres()->attach(++$id);
        }
    }
}