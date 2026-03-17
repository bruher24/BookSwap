<?php

namespace App\Interfaces;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

interface MessageServiceInterface
{
    public function create(array $data): Message|false;

    public function get(string $id): Message|false;

    public function getAll(): Collection;

    public function where(string $field, string $value): Collection;

    public function update(Message $message, array $data): Message|false;

    public function delete(Message $message): bool;
}
