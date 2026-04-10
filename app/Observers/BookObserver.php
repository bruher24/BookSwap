<?php

namespace App\Observers;

use App\Events\BookCreated;
use App\Events\BookDeleted;
use App\Events\BookForceDeleted;
use App\Events\BookRestored;
use App\Events\BookUpdated;
use App\Models\Book;

final class BookObserver
{
    /**
     * Handle the Book "created" event.
     */
    public function created(Book $book): void
    {
        BookCreated::dispatch($book);
    }

    /**
     * Handle the Book "updated" event.
     */
    public function updated(Book $book): void
    {
        BookUpdated::dispatch($book);
    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book): void
    {
        BookDeleted::dispatch($book);
    }

    /**
     * Handle the Book "restored" event.
     */
    public function restored(Book $book): void
    {
        BookRestored::dispatch($book);
    }

    /**
     * Handle the Book "force deleted" event.
     */
    public function forceDeleted(Book $book): void
    {
        BookForceDeleted::dispatch($book);
    }
}
