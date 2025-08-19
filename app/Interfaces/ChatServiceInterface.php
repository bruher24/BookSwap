<?php

namespace App\Interfaces;

use App\Models\Chat;

interface ChatServiceInterface extends ServiceInterface
{
    public function create(array $data): Chat|false;

    public function get(int $id): Chat|false;
}
