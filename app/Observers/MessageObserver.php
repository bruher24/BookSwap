<?php

namespace App\Observers;

use App\Events\MessageCreatedEvent;
use App\Models\Message;

final class MessageObserver
{
    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message): void
    {
        MessageCreatedEvent::dispatch($message);
    }

    /**
     * Handle the Message "updated" event.
     */
    public function updated(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "deleted" event.
     */
    public function deleted(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "restored" event.
     */
    public function restored(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "force deleted" event.
     */
    public function forceDeleted(Message $message): void
    {
        //
    }
}
