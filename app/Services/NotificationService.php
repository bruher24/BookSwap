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
}
