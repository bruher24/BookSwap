<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.{first_id}.{second_id}', function ($user, $first_id, $second_id) {
    return (int)$user->id === (int)$first_id || (int)$user->id === (int)$second_id;
});
