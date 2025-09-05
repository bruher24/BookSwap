<?php

namespace App\Interfaces;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface extends ServiceInterface
{
    public function create(array $data): User|false;

    public function get(int $id): User|false;

    public function updateSettings(User $user, array $data): bool;

    public function addToFavorites($user_id, $book_id): bool;

    public function removeFromFavorites($user_id, $book_id): bool;

    public function chats(User $user): Collection;

    public function getChat(User $user, User $recipient): Chat|false;

    public function sendMessage(User $user, User $recipient, string $body): Message|false;

    public function getUserNotifications(User $user): Collection;

    public function checkOneNotification(User $user, int $notificationId): bool;

    public function checkManyNotifications(User $user, array $notificationIds): bool;

    public function getUnreadMessages(User $user): Collection;

    public function readMessages(User $user, array $messagesToRead): bool;

    public function updateNotifications(Collection $notifications): bool;
}
