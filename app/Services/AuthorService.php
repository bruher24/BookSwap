<?php

namespace App\Services;

use App\Interfaces\AuthorServiceInterface;
use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;

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

    public function getAll(): Collection
    {
        return Author::whereHas('books')->get();
    }
}
