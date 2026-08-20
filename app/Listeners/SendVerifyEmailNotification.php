<?php

namespace App\Listeners;

use App\Events\UserCreatedEvent;
use App\Mail\VerifyEmailMail;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

final class SendVerifyEmailNotification implements ShouldQueue
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
    public function handle(UserCreatedEvent $event): void
    {
        $event->user->notify(new VerifyEmailNotification());
    }
}
