<?php

namespace App\Listeners;

use App\Events\TradeOfferCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTradeOfferNotification implements ShouldQueue
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
    public function handle(TradeOfferCreated $event): void
    {
        //
    }
}
