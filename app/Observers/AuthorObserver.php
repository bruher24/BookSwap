<?php

namespace App\Observers;

use App\Events\AuthorCreatedEvent;
use App\Events\AuthorDeletedEvent;
use App\Events\AuthorForceDeletedEvent;
use App\Events\AuthorRestoredEvent;
use App\Events\AuthorUpdatedEvent;
use App\Models\Author;

final class AuthorObserver
{
    /**
     * Handle the Author "created" event.
     */
    public function created(Author $author): void
    {
        AuthorCreatedEvent::dispatch($author);
    }

    /**
     * Handle the Author "updated" event.
     */
    public function updated(Author $author): void
    {
        AuthorUpdatedEvent::dispatch($author);
    }

    /**
     * Handle the Author "deleted" event.
     */
    public function deleted(Author $author): void
    {
        AuthorDeletedEvent::dispatch($author);
    }

    /**
     * Handle the Author "restored" event.
     */
    public function restored(Author $author): void
    {
        AuthorRestoredEvent::dispatch($author);
    }

    /**
     * Handle the Author "force deleted" event.
     */
    public function forceDeleted(Author $author): void
    {
        AuthorForceDeletedEvent::dispatch($author);
    }
}
