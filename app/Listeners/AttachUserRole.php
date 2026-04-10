<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\Role;
use Illuminate\Contracts\Queue\ShouldQueue;

final class AttachUserRole implements ShouldQueue
{
    public string $queue = 'listeners';

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
