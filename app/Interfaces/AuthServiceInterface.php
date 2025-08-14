<?php

namespace App\Interfaces;

use App\Models\User;

interface AuthServiceInterface
{
    public function auth(array $credentials): string;

    public function login(array $credentials): bool;

    public function currentUser(): ?User;

    public function logout(): void;

}
