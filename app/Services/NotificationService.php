<?php

namespace App\Services;

use App\Interfaces\NotificationServiceInterface;
use App\Models\Notification;

class NotificationService extends Service implements NotificationServiceInterface
{
    public function __construct()
    {
        parent::__construct(Notification::class);
    }

    public function create(array $data): Notification|false
    {
        return parent::create($data);
    }

    public function get(int $id): Notification|false
    {
        return parent::get($id);
    }
}
