<?php

namespace App\Listeners;

use App\Events\TradeOfferCreatedEvent;
use App\Models\User;
use App\Notifications\TradeOfferCreatedNotification;

final class SendTradeOfferReceivedNotification
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
    public function handle(TradeOfferCreatedEvent $event): void
    {
        $sender = User::find($event->tradeOffer->sender_id);
        $receiver = User::find($event->tradeOffer->receiver_id);

        if (!$sender || !$receiver) {
            return;
        }

        $receiver->notify(new TradeOfferCreatedNotification($event->tradeOffer, $sender));
    }
}
