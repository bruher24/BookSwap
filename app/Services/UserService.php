<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService extends Service
{
    public function __construct()
    {
        parent::__construct(new UserRepository());
    }

    public function books(int $userId, array $filters = []): array
    {
        $booksService = new BookService();
        $books = $booksService->byUser($userId, $filters);
        $params = $booksService->params($userId);
        return [$books, $params];
    }
}