<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService extends Service
{
    public function __construct()
    {
        parent::__construct(new UserRepository());
    }

    public function books(int $userId): array
    {
        $booksService = new BookService();
        $books = $booksService->byUser($userId);
        $params = $booksService->params($books);
        return [$books, $params];
    }
}