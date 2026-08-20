<?php

namespace App\Events;

use App\Http\Resources\TradeOfferResource;
use App\Models\TradeOffer;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\Attributes\Connection;
use Illuminate\Queue\Attributes\Queue;
use Illuminate\Queue\SerializesModels;
use Override;

#[Queue('reverb')]
final class TradeOfferUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     * @psalm-suppress PossiblyNullPropertyAssignmentValue
     */
    public function __construct(
        public TradeOffer $tradeOffer,
        public User $updatedUser
    ) {
    }

    #[Override]
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('users.' . $this->tradeOffer->sender_id),
            new PrivateChannel('users.' . $this->tradeOffer->receiver_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'trade-offer.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'tradeOffer' => new TradeOfferResource($this->tradeOffer),
        ];
    }
}
