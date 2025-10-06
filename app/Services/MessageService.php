<?php

namespace App\Services;

use App\Interfaces\MessageServiceInterface;
use App\Models\Message;
use Override;

class MessageService extends Service implements MessageServiceInterface
{
    public function __construct()
    {
        parent::__construct(Message::class);
    }

    #[Override]
    public function create(array $data): Message|false
    {
        return parent::create($data);
    }

    #[Override]
    public function get(string $id): Message|false
    {
        return parent::get($id);
    }
}
