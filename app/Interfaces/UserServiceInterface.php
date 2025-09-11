<?php

namespace App\Interfaces;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface extends ServiceInterface
{
    public function create(array $data): User|false;

    public function get(string $id): User|false;

    public function chats(string $user_id): Collection;

    public function sendMessage(string $user_id, string $recipient_id, string $body): Message|false;

    public function getUserNotifications(string $user_id): Collection;

    public function checkOneNotification(string $user_id, int $notificationId): bool;

    public function checkManyNotifications(string $user_id, array $notificationIds): bool;

    public function getUnreadMessages(string $user_id): Collection|false;

    public function readMessages(string $user_id, array $messagesToRead): bool;

    public function updateNotifications(Collection $notifications): bool;
}
