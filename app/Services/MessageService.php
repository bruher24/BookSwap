<?php

namespace App\Services;

use App\Interfaces\MessageServiceInterface;
use App\Models\Message;

class MessageService extends Service implements MessageServiceInterface
{
    public function __construct()
    {
        parent::__construct(Message::class);
    }

    public function create(array $data): Message|false
    {
        return parent::create($data);
    }

    public function get(int $id): Message|false
    {
        return parent::get($id);
    }
}
