<?php

namespace App\Listeners;

use App\Events\TradeOfferUpdatedEvent;
use App\Models\User;
use App\Notifications\TradeOfferUpdatedNotification;

final class SendTradeOfferUpdatedNotification
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
    public function handle(TradeOfferUpdatedEvent $event): void
    {
        $userToNotify = User::find($event->tradeOffer->receiver_id == $event->updatedUser->id
            ? $event->tradeOffer->sender_id
            : $event->tradeOffer->receiver_id);

        if (!$userToNotify) {
            return;
        }

        $userToNotify->notify(new TradeOfferUpdatedNotification($event->tradeOffer));

    }
}
