<?php

namespace App\Observers;

use App\Events\TradeOfferCreated;
use App\Events\TradeOfferDeleted;
use App\Events\TradeOfferForceDeleted;
use App\Events\TradeOfferRestored;
use App\Events\TradeOfferUpdated;
use App\Models\TradeOffer;

final class TradeOfferObserver
{
    /**
     * Handle the TradeOffer "created" event.
     */
    public function created(TradeOffer $tradeOffer): void
    {
        TradeOfferCreated::dispatch($tradeOffer);
    }

    /**
     * Handle the TradeOffer "updated" event.
     */
    public function updated(TradeOffer $tradeOffer): void
    {
        TradeOfferUpdated::dispatch($tradeOffer);
    }

    /**
     * Handle the TradeOffer "deleted" event.
     */
    public function deleted(TradeOffer $tradeOffer): void
    {
        TradeOfferDeleted::dispatch($tradeOffer);
    }

    /**
     * Handle the TradeOffer "restored" event.
     */
    public function restored(TradeOffer $tradeOffer): void
    {
        TradeOfferRestored::dispatch($tradeOffer);
    }

    /**
     * Handle the TradeOffer "force deleted" event.
     */
    public function forceDeleted(TradeOffer $tradeOffer): void
    {
        TradeOfferForceDeleted::dispatch($tradeOffer);
    }
}
