<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Mail\EmailVerification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

final class SendEmailVerification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the name of the listener's queue connection.
     */
    public function viaConnection(): string
    {
        return 'rabbitmq';
    }

    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return 'listeners';
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        $mail = (new EmailVerification($event->user))
            ->onConnection('redis')
            ->onQueue('mail');

        Mail::to($event->user)->queue($mail);
    }
}
