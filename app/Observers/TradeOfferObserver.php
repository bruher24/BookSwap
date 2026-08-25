<?php

namespace App\Observers;

use App\Events\TradeOfferCreatedEvent;
use App\Events\TradeOfferDeletedEvent;
use App\Events\TradeOfferForceDeletedEvent;
use App\Events\TradeOfferRestoredEvent;
use App\Events\TradeOfferUpdatedEvent;
use App\Models\TradeOffer;
use Illuminate\Support\Facades\Auth;

final class TradeOfferObserver
{
    /**
     * Handle the TradeOffer "created" event.
     */
    public function created(TradeOffer $tradeOffer): void
    {
        TradeOfferCreatedEvent::dispatch($tradeOffer);
    }

    /**
     * Handle the TradeOffer "updated" event.
     * @psalm-suppress PossiblyNullArgument
     */
    public function updated(TradeOffer $tradeOffer): void
    {
        TradeOfferUpdatedEvent::dispatch($tradeOffer, Auth::user());
    }

    /**
     * Handle the TradeOffer "deleted" event.
     */
    public function deleted(TradeOffer $tradeOffer): void
    {
        TradeOfferDeletedEvent::dispatch($tradeOffer);
    }

    /**
     * Handle the TradeOffer "restored" event.
     */
    public function restored(TradeOffer $tradeOffer): void
    {
        TradeOfferRestoredEvent::dispatch($tradeOffer);
    }

    /**
     * Handle the TradeOffer "force deleted" event.
     */
    public function forceDeleted(TradeOffer $tradeOffer): void
    {
        TradeOfferForceDeletedEvent::dispatch($tradeOffer);
    }
}
