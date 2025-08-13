<?php

namespace App\Services;

use App\Interfaces\ChatServiceInterface;
use App\Models\Chat;

class ChatService extends Service implements ChatServiceInterface
{
    public function __construct()
    {
        parent::__construct(Chat::class);
    }
}
