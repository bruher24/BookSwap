<?php

namespace App\Interfaces;

use App\Models\Message;

interface MessageServiceInterface extends ServiceInterface
{
    public function create(array $data): Message|false;

    public function get(string $id): Message|false;
}
