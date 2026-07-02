<?php

namespace Database\Seeders;

use App\Enums\BookCondition;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookType;
use App\Models\Cover;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Seeder;

final class BookSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@admin.com')->firstOrFail();
        $seller = User::query()->where('email', 'seller@seller.com')->firstOrFail();
        $buyer = User::query()->where('email', 'buyer@buyer.com')->firstOrFail();
        $baseCover = Cover::query()->where('src', 'covers/cover.png')->firstOrFail();

        $books = [
            [
                'user_id' => $admin->id,
                'name' => 'Тестовая книга',
                'publishing_house' => 'Питер',
                'publication_year' => '2020',
                'isbn' => str_repeat('1', 13),
                'page_count' => 300,
                'condition' => BookCondition::Good,
                'book_type' => 'Твердая обложка',
                'authors' => ['Тестов'],
                'genres' => ['Учебник', 'test1'],
            ],
            [
                'user_id' => $seller->id,
                'name' => 'Онегин',
                'publishing_house' => 'ЕЕЕ',
                'publication_year' => '2021',
                'isbn' => str_repeat('2', 13),
                'page_count' => 400,
                'condition' => BookCondition::Normal,
                'book_type' => 'Мягкая обложка',
                'authors' => ['Пушкин'],
                'genres' => ['Детектив', 'test2'],
            ],
            [
                'user_id' => $buyer->id,
                'name' => 'Зов Ктулху',
                'publishing_house' => 'фывфыв',
                'publication_year' => '2022',
                'isbn' => str_repeat('3', 13),
                'page_count' => 666,
                'condition' => BookCondition::Perfect,
                'book_type' => 'Твердая обложка',
                'authors' => ['Лавкрафт'],
                'genres' => ['Фантастика', 'test3'],
            ],
            [
                'user_id' => $admin->id,
                'name' => 'ГОСТ 2281337',
                'publishing_house' => 'минобр',
                'publication_year' => '1978',
                'isbn' => str_repeat('4', 13),
                'page_count' => 3,
                'condition' => BookCondition::Terrible,
                'book_type' => 'Другое',
                'authors' => ['Министерство'],
                'genres' => ['Учебник', 'test4'],
            ],
        ];

        foreach ($books as $bookData) {
            $authorLastnames = $bookData['authors'];
            $genreNames = $bookData['genres'];
            $bookTypeName = $bookData['book_type'];
            unset($bookData['authors'], $bookData['genres'], $bookData['book_type']);

            $bookType = BookType::query()->where('name', $bookTypeName)->firstOrFail();
            $bookData['cover_id'] = $baseCover->id;
            $bookData['book_type_id'] = $bookType->id;
            $bookData['is_available'] = true;

            $book = Book::query()->updateOrCreate(
                ['isbn' => $bookData['isbn']],
                $bookData
            );

            $authorIds = Author::query()
                ->whereIn('lastname', $authorLastnames)
                ->pluck('id')
                ->all();
            $genreIds = Genre::query()
                ->whereIn('name', $genreNames)
                ->pluck('id')
                ->all();

            $book->authors()->syncWithoutDetaching($authorIds);
            $book->genres()->syncWithoutDetaching($genreIds);
        }

        $seller->favorites()->syncWithoutDetaching([
            Book::query()->where('isbn', str_repeat('3', 13))->firstOrFail()->id,
        ]);
        $buyer->favorites()->syncWithoutDetaching([
            Book::query()->where('isbn', str_repeat('2', 13))->firstOrFail()->id,
        ]);
    }
}
