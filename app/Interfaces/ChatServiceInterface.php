<?php

namespace App\Interfaces;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use Override;

interface ChatServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): Chat|false;

    #[Override]
    public function get(string $id): Chat|false;

    #[Override]
    public function update(string $id, array $data): Chat|false;

    public function byUser(string $userId): Chat|false;

    public function messages(string $chatId): Collection;

    public function sendMessage(string $chatId, string $senderId, string $body): Message|false;
}
