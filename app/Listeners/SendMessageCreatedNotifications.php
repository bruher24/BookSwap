<?php

namespace App\Listeners;

use App\Events\MessageCreated;
use App\Models\User;
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
        $sender = User::find($event->message->sender_id);

        if (!$sender) {
            return;
        }

        $event->receiver->notify(new MessageCreatedNotification($event->message, $sender));
    }
}
