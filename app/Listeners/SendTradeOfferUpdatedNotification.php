<?php

namespace App\Listeners;

use App\Enums\TradeOfferStatus;
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
        switch ($event->newStatus) {
            case TradeOfferStatus::Accepted:
                // TODO: отправить уведомление о принятии
                break;
            case TradeOfferStatus::Rejected:
                // TODO: отправить уведомление об отклонении
                break;
            case TradeOfferStatus::Finished:
                // TODO: отправить уведомление о завершении
                break;
        }
    }
}
