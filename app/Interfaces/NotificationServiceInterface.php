<?php

namespace App\Interfaces;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface NotificationServiceInterface
{
    public function create(array $data): Notification|false;

    public function get(int $id): Notification|false;

    public function getAll(): Collection;

    public function where(string $field, string $value): Collection;

    public function update(Notification $notification, array $data): Notification|false;

    public function delete(Notification $notification): bool;

    public function read(Notification $notification): bool;

    public function byUser(User $user): Collection;

    public function readAll(User $user): bool;

}
