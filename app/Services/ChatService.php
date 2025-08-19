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

    public function create(array $data): Chat|false
    {
        return parent::create($data);
    }

    public function get(int $id): Chat|false
    {
        return parent::get($id);
    }
}
