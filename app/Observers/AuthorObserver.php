<?php

namespace App\Observers;

use App\Events\AuthorCreated;
use App\Events\AuthorDeleted;
use App\Events\AuthorForceDeleted;
use App\Events\AuthorRestored;
use App\Events\AuthorUpdated;
use App\Models\Author;

final class AuthorObserver
{
    /**
     * Handle the Author "created" event.
     */
    public function created(Author $author): void
    {
        AuthorCreated::dispatch($author);
    }

    /**
     * Handle the Author "updated" event.
     */
    public function updated(Author $author): void
    {
        AuthorUpdated::dispatch($author);
    }

    /**
     * Handle the Author "deleted" event.
     */
    public function deleted(Author $author): void
    {
        AuthorDeleted::dispatch($author);
    }

    /**
     * Handle the Author "restored" event.
     */
    public function restored(Author $author): void
    {
        AuthorRestored::dispatch($author);
    }

    /**
     * Handle the Author "force deleted" event.
     */
    public function forceDeleted(Author $author): void
    {
        AuthorForceDeleted::dispatch($author);
    }
}
