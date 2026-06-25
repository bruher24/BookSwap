<?php

namespace App\Interfaces;

interface AuthServiceInterface
{
    public function verifyEmail(int $userId): bool;
}
