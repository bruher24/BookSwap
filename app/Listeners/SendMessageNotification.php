<?php

namespace App\Listeners;

use App\Events\MessageReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMessageNotification implements ShouldQueue
{
    public string $connection = 'redis';
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
    public function handle(MessageReceived $event): void
    {
    }
}
