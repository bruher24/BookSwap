<?php

namespace App\Repositories;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;

class BookRepository extends Repository
{
    public function __construct()
    {
        parent::__construct(new Book());
    }

    public function where(array $conditions): Collection
    {
        dd($conditions);
        $genres = Genre::whereIn('id', $conditions['genres'])->get();
        $authors = Author::whereIn('id', $conditions['authors'])->get();
        $books = Book::whereAttachedTo($genres, $authors)->get();
        return $books;
    }
}