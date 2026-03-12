<?php

namespace App\Events;

use App\Models\TradeOffer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class TradeOfferDeleted implements ShouldQueue
{
    use Dispatchable;
    use SerializesModels;

    public string $connection = 'redis';

    public string $queue = 'listeners';

    /**
     * Create a new event instance.
     */
    public function __construct(
        public TradeOffer $tradeOffer
    ) {
    }
}
