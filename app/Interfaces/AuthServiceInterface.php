<?php

namespace App\Interfaces;

use App\Models\User;

interface AuthServiceInterface
{
    public function login(array $credentials): bool;

    public function logout(): bool;

    public function currentUser(): ?User;

    public function refreshToken(User $user): string;
}
