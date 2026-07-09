<?php

namespace App\Listeners;

use App\Events\TradeOfferUpdated;
use App\Notifications\TradeOfferUpdatedNotification;

class SendTradeOfferUpdatedNotifications
{
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
        $event->userToNotify->notify(new TradeOfferUpdatedNotification($event->tradeOffer));

    }
}
