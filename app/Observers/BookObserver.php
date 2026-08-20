<?php

namespace App\Observers;

use App\Events\BookCreatedEvent;
use App\Events\BookDeletedEvent;
use App\Events\BookForceDeletedEvent;
use App\Events\BookRestoredEvent;
use App\Events\BookUpdatedEvent;
use App\Models\Book;

final class BookObserver
{
    /**
     * Handle the Book "created" event.
     */
    public function created(Book $book): void
    {
        BookCreatedEvent::dispatch($book);
    }

    /**
     * Handle the Book "updated" event.
     */
    public function updated(Book $book): void
    {
        BookUpdatedEvent::dispatch($book);
    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book): void
    {
        BookDeletedEvent::dispatch($book);
    }

    /**
     * Handle the Book "restored" event.
     */
    public function restored(Book $book): void
    {
        BookRestoredEvent::dispatch($book);
    }

    /**
     * Handle the Book "force deleted" event.
     */
    public function forceDeleted(Book $book): void
    {
        BookForceDeletedEvent::dispatch($book);
    }
}
