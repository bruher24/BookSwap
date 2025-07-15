<?php

namespace App\Services;

use App\Interfaces\UserServiceInterface;
use App\Models\User;

class UserService extends Service implements UserServiceInterface
{
    public function __construct()
    {
        parent::__construct(User::class);
    }
}