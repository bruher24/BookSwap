<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Mail\VerifyYourEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        $message = (new VerifyYourEmail($event->user, '', '1'))
            ->onConnection('redis')
            ->onQueue('mail');
        Log::debug('Going to send welcome email');
        //        Mail::to($event->user)
        //            ->queue($message);
        Log::debug('Sent');
    }
}
