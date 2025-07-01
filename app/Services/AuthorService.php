<?php

namespace App\Services;

use App\Repositories\AuthorRepository;

class AuthorService extends Service
{
    public function __construct()
    {
        parent::__construct(new AuthorRepository());
        $this->ucFirstFields = [
            'lastname',
            'firstname',
            'patronymic',
        ];
    }

    public function books(array $filters = []): array
    {
        $booksService = new BookService();
        $books = $booksService->where($filters);
        $allBooks = $booksService->where(['author' => [$filters['author']]]);
        $params = $this->params($allBooks);
        return [$books, $params];
    }

    public function params($books): array
    {
        $params = parent::params($books);
        unset($params['authors']);
        return $params;
    }
}
