<?php

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/**
 * @psalm-suppress MissingClosureParamType
 */
Broadcast::channel('users.{channelUser}', function (User $authUser, User $channelUser) {
    return $authUser->is($channelUser);
});

Broadcast::channel('chats.{chat}', function (User $user, Chat $chat) {
    return $chat->isUserBelongs($user);
});
