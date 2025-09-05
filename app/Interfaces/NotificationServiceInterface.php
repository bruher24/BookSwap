<?php

namespace App\Interfaces;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;

interface NotificationServiceInterface extends ServiceInterface
{
    public function create(array $data): Notification|false;

    public function get(int $id): Notification|false;

    public function byUser(string $user_id): Collection;

    public function readAll(string $user_id): bool;
}
