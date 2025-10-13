<?php

namespace App\Listeners;

use App\Events\MessageSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendMessageNotification implements ShouldQueue
{
    public string $connection = 'redis';

    public string $queue = 'listeners';

    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        Log::debug('Запуск слушателя');
        Log::debug('Трансляция на ' . $event->broadcastOn()[0]->name);
    }
}
