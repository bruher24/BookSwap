<?php

namespace App\Interfaces;

use App\Models\Notification;

interface NotificationServiceInterface extends ServiceInterface
{
    public function create(array $data): Notification|false;

    public function get(int $id): Notification|false;
}
