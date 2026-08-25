<?php

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/**
 * @psalm-suppress UnusedClosureParam
 */
Broadcast::channel('authors', function (User $user) {
    return true;
});

/**
 * @psalm-suppress UnusedClosureParam
 */
Broadcast::channel('books', function (User $user) {
    return true;
});

Broadcast::channel('chats.{chat}', function (User $user, Chat $chat) {
    return $chat->isUserBelongs($user);
});

Broadcast::channel('users.{channelUser}', function (User $authUser, User $channelUser) {
    return $authUser->is($channelUser);
});

