<?php

namespace App\Interfaces;

interface AuthServiceInterface
{
    public function verifyEmail(string $userId): bool;
}
