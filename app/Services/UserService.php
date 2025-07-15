<?php

namespace App\Services;

use App\Models\User;

class UserService extends Service
{
    public function __construct()
    {
        parent::__construct(User::class);
    }
}