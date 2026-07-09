<?php

namespace App\Listeners;

use App\Events\MessageCreated;
use App\Notifications\MessageCreatedNotification;

final class SendMessageCreatedNotifications
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
    public function handle(MessageCreated $event): void
    {
        $event->receiver->notify(new MessageCreatedNotification($event->message));
    }
}
