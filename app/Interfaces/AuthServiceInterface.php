<?php

namespace App\Interfaces;

interface AuthServiceInterface
{
    public function login(array $credentials): bool;

    public function refreshToken(string $email): string;

    public function logout(string $email): bool;
}
