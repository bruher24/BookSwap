<?php

namespace App\Services;

use App\Repositories\AuthorRepository;

class AuthorService extends Service
{
    public function __construct()
    {
        parent::__construct(new AuthorRepository());
    }
}
