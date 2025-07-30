<?php

namespace App\Interfaces;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

interface UserServiceInterface extends ServiceInterface
{
    public function updateSettings(User $user, array $data): bool;

    public function addToFavorites($user_id, $book_id): void;

    public function removeFromFavorites($user_id, $book_id): void;

    public function getUserChats(User $user): Collection;

    public function getMessages(User $user, User $recipient): Collection;

    public function sendMessage(User $user, User $recipient, string $body): JsonResponse;

    public function getUserNotifications(User $user): Collection;

    public function checkOneNotifications(User $user, int $notificationId): JsonResponse;

    public function checkManyNotifications(User $user, array $notifications): JsonResponse;

    public function checkAllNotifications(User $user): JsonResponse;
}
