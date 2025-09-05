<?php

namespace App\Services;

use App\Interfaces\AuthorServiceInterface;
use App\Models\Author;

class AuthorService extends Service implements AuthorServiceInterface
{
    public function create(array $data): Author|false
    {
        return parent::create($data);
    }

    public function get(int $id): Author|false
    {
        return parent::get($id);
    }

    public function __construct()
    {
        parent::__construct(Author::class);
        $this->ucFirstFields = [
            'lastname',
            'firstname',
            'patronymic',
        ];
    }
}
