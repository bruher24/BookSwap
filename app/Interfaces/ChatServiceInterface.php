<?php

namespace App\Interfaces;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ChatServiceInterface
{
    public function create(array $data): Chat|false;

    public function get(string $id): Chat|false;

    public function getAll(): Collection;

    public function where(string $field, string $value): Collection;

    public function update(Chat $chat, array $data): Chat|false;

    public function delete(Chat $chat): bool;

    public function byUser(User $user): Collection;

    public function messages(Chat $chat): Collection;

    public function sendMessage(Chat $chat, User $sender, string $body): Message|false;
}
