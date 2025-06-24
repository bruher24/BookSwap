<?php

namespace App\Repositories;

use App\Models\Author;

class AuthorRepository extends Repository
{
    public function __construct()
    {
        parent::__construct(new Author());
    }
}
