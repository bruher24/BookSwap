<?php

namespace App\Interfaces;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Override;

interface NotificationServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): Notification|false;

    #[Override]
    public function get(string $id): Notification|false;

    public function byUser(string $user_id): Collection;

    public function readAll(string $user_id): bool;

    #[Override]
    public function update(string $id, array $data): Notification|false;
}
