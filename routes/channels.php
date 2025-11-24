<?php

use Illuminate\Support\Facades\Broadcast;

/**
 * @psalm-suppress MissingClosureParamType
 */
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});
