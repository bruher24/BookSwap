<?php

namespace App\Events;

use App\Models\TradeOffer;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class TradeOfferForceDeletedEvent
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public TradeOffer $tradeOffer
    ) {
    }
}
