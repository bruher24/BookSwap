<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Mail\EmailVerification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

final class SendEmailVerification implements ShouldQueue
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
    public function handle(UserCreated $event): void
    {
        $mail = (new EmailVerification($event->user))
            ->onQueue('mail');

        Mail::to($event->user)->queue($mail);
    }
}
