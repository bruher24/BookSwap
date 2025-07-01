<?php

namespace App\Services;

use App\Repositories\GenreRepository;

class GenreService extends Service
{
    public function __construct()
    {
        parent::__construct(new GenreRepository());
        $this->ucFirstFields = [
            'name',
        ];
    }

    public function books(array $filters = []): array
    {
        $booksService = new BookService();
        $books = $booksService->where($filters);
        $allBooks = $booksService->where(['genre' => [$filters['genre']]]);
        $params = $this->params($allBooks);
        return [$books, $params];
    }

    public function params($books): array
    {
        $params = parent::params($books);
        unset($params['genres']);
        return $params;
    }
}
