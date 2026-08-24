<?php

namespace App\Events;

use App\Http\Resources\TradeOfferResource;
use App\Models\TradeOffer;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\Attributes\Queue;
use Illuminate\Queue\SerializesModels;
use Override;

#[Queue('reverb')]
final class TradeOfferCreatedEvent implements ShouldBroadcast
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     * @psalm-suppress PossiblyNullPropertyAssignmentValue
     */
    public function __construct(
        public TradeOffer $tradeOffer
    ) {
    }

    /**
     * @psalm-suppress InvalidOperand
     */
    #[Override]
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('users.' . $this->tradeOffer->receiver_id),
            new PrivateChannel('users.' . $this->tradeOffer->sender_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'trade-offer.created';
    }

    public function broadcastWith(): array
    {
        return [
            'tradeOffer' => new TradeOfferResource($this->tradeOffer),
        ];
    }
}
