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
}
