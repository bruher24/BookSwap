<?php

namespace App\Listeners;

use App\Events\TradeOfferCreated;
use App\Mail\TradeOfferReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

final class SendTradeOfferCreatedNotification implements ShouldQueue
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
    public function handle(TradeOfferCreated $event): void
    {
        $mail = (new TradeOfferReceived($event->tradeOffer, $event->receiver))
            ->onQueue('mail');

        Mail::to($event->receiver)->queue($mail);
    }
}
