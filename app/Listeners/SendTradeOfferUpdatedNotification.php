<?php

namespace App\Listeners;

use App\Events\TradeOfferUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

final class SendTradeOfferUpdatedNotification implements ShouldQueue
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
    public function handle(TradeOfferUpdated $event): void
    {
        //
    }
}
