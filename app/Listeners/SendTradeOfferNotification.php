<?php

namespace App\Listeners;

use App\Events\TradeOfferCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

final class SendTradeOfferNotification implements ShouldQueue
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
     * @psalm-suppress UnusedParam
     */
    public function handle(TradeOfferCreated $event): void
    {
        //
    }
}
