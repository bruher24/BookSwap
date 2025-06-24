<?php

namespace App\Services;

use App\Repositories\BookRepository;

class BookService extends Service
{
    public function __construct()
    {
        parent::__construct(new BookRepository());
    }
}