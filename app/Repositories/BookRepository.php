<?php

namespace App\Repositories;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BookRepository extends Repository
{
    public function __construct()
    {
        parent::__construct(new Book());
    }

    public function byUser(int $userId, array $filters = []): Collection
    {
        $books = $this->where($filters);
        return $books->where('user_id', $userId);
    }

    public function where(array $conditions): Collection
    {
        if (empty($conditions)) {
            return $this->getAll();
        }

        $books = Book::where('deleted_at', null);

        if (isset($conditions['genre'])) {
            $genres = Genre::whereIn('id', $conditions['genre'])->get();
            $books->whereHas('genres', function ($query) use ($genres) {
                $query->whereIn('id', $genres->pluck('id'));
            });
        }

        if (isset($conditions['author'])) {
            $authors = Author::whereIn('id', $conditions['author'])->get();
            $books->whereHas('authors', function ($query) use ($authors) {
                $query->whereIn('id', $authors->pluck('id'));
            });
        }

        if (isset($conditions['year'])) {
            $books->whereIn('publication_year', $conditions['year']);
        }

        if (isset($conditions['type'])) {
            $books->whereIn('type_id', $conditions['type']);
        }
        return $books->get();
    }

    public function create(array $data): Model
    {
        $book = parent::create($data);
        $book->authors()->attach();

    }
}