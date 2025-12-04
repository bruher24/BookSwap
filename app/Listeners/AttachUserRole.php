<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\Role;

final class AttachUserRole
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        $user = $event->user;
        $user->roles()->attach(Role::USER_ROLE_ID);
    }
}
