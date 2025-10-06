<?php

namespace App\Services;

use App\Interfaces\AuthorServiceInterface;
use App\Models\Author;
use Override;

class AuthorService extends Service implements AuthorServiceInterface
{
    public function __construct()
    {
        parent::__construct(Author::class);
        $this->ucFirstFields = [
            'lastname',
            'firstname',
            'patronymic',
        ];
    }

    #[Override]
    public function create(array $data): Author|false
    {
        return parent::create($data);
    }

    #[Override]
    public function get(string $id): Author|false
    {
        return parent::get($id);
    }
}
