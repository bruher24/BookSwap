<?php

namespace App\Events;

use App\Models\TradeOffer;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class TradeOfferCreated
{
    use Dispatchable;
    use SerializesModels;

    public User $receiver;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public TradeOffer $tradeOffer
    ) {
        $this->receiver = User::find($this->tradeOffer->receiver_id);
    }
}
