<?php

namespace App\Interfaces;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Override;

interface ChatServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): Chat|false;

    #[Override]
    public function get(string $id): Chat|false;

    public function byUsers(string $user_id, string $recipient_id): Chat|false;

    public function messages(string $chat_id): Collection;

    public function sendMessage(string $user_id, string $recipient_id, string $body): Message|false;

    public function update(string $id, array $data): Chat|false;
}
