<?php

namespace App\Interfaces;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

interface MessageServiceInterface
{
    public function create(array $data): Message|false;

    public function get(int $id): Message|false;

    public function getAll(): Collection;

    public function update(Message $message, array $data): Message|false;

    public function delete(Message $message): bool;
}
