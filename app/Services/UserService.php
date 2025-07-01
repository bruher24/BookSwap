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
        [$books, $params] = $booksService->byUser($userId, $filters);
        return [$books, $params];
    }
}