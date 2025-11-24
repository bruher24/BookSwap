<?php

namespace App\Services;

use App\Interfaces\NotificationServiceInterface;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Override;

final class NotificationService extends Service implements NotificationServiceInterface
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct()
    {
        parent::__construct(Notification::class);
    }

    #[Override]
    public function create(array $data): Notification|false
    {
        return parent::create($data);
    }

    #[Override]
    public function get(string $id): Notification|false
    {
        return parent::get($id);
    }

    #[Override]
    public function byUser(string $user_id): Collection
    {
        return Notification::where('user_id', $user_id)->get();
    }

    #[Override]
    public function readAll(string $user_id): bool
    {
        return !!Notification::where('user_id', $user_id)->update(['seen' => true]);
    }
}
