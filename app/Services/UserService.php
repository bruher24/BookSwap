<?php

namespace App\Services;

use App\Models\User;

final class UserService extends Service
{
    public function __construct()
    {
        parent::__construct(User::class);
    }
}