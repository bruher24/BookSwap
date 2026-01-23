<?php

namespace App\Listeners;

use App\Events\TradeOfferCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

final class SendTradeOfferNotification implements ShouldQueue
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
        return 'redis';
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
     * @psalm-suppress UnusedParam
     */
    public function handle(TradeOfferCreated $event): void
    {
        //
    }
}
