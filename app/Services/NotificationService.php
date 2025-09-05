<?php

namespace App\Services;

use App\Interfaces\NotificationServiceInterface;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;

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

    public function byUser(string $user_id): Collection
    {
        return Notification::where('user_id', $user_id)->get();
    }

    public function readAll(string $user_id): bool
    {
        return Notification::where('user_id', $user_id)->update(['seen' => true]);
    }
}
