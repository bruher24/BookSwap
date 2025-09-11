<?php

namespace App\Interfaces;

use App\Models\Chat;

interface ChatServiceInterface extends ServiceInterface
{
    public function create(array $data): Chat|false;

    public function get(string $id): Chat|false;

    public function byUsers(string $user_id, string $recipient_id): Chat|false;
}
