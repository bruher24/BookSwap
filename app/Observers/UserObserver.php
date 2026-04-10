<?php

namespace App\Observers;

use App\Events\UserCreated;
use App\Events\UserDeleted;
use App\Events\UserForceDeleted;
use App\Events\UserRestored;
use App\Events\UserUpdated;
use App\Models\User;

final class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        UserCreated::dispatch($user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        UserUpdated::dispatch($user);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        UserDeleted::dispatch($user);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        UserRestored::dispatch($user);
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        UserForceDeleted::dispatch($user);
    }
}
