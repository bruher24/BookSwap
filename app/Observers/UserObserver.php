<?php

namespace App\Observers;

use App\Events\UserCreatedEvent;
use App\Events\UserDeletedEvent;
use App\Events\UserForceDeletedEvent;
use App\Events\UserRestoredEvent;
use App\Events\UserUpdatedEvent;
use App\Models\User;

final class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        UserCreatedEvent::dispatch($user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        UserUpdatedEvent::dispatch($user);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        UserDeletedEvent::dispatch($user);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        UserRestoredEvent::dispatch($user);
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        UserForceDeletedEvent::dispatch($user);
    }
}
