<?php

namespace App\Events;

use App\Enums\TradeOfferStatus;
use App\Models\TradeOffer;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class TradeOfferUpdated
{
    use Dispatchable;
    use SerializesModels;

    public User $receiver;

    /**
     * Create a new event instance.
     * @psalm-suppress PossiblyNullPropertyAssignmentValue
     */
    public function __construct(
        public TradeOffer $tradeOffer,
        public TradeOfferStatus $newStatus
    ) {
        $this->receiver = User::find($this->tradeOffer->receiver_id);
    }
}
