<?php

namespace App\Listeners;

use App\Events\TradeOfferCreated;
use App\Models\User;

final class SendTradeOfferCreatedNotifications
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
    public function handle(TradeOfferCreated $event): void
    {
        // TODO: добавить уведомление
        User::find($event->tradeOffer->receiver_id)
            ->notify(new TradeOfferCreatedNotification($event->tradeOffer));
    }
}
