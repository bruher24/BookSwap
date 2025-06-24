<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository extends Repository
{
    public function __construct()
    {
        parent::__construct(new Book());
    }
}