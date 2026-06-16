<?php

namespace App\Listeners;

use App\Events\TradeOfferUpdated;
use App\Mail\TradeOfferStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

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
        $mail = (new TradeOfferStatusUpdated($event->tradeOffer, $event->receiver))
            ->onQueue('mail');

        Mail::to($event->receiver)->queue($mail);
    }
}
