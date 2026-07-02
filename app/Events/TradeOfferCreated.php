<?php

namespace App\Events;

use App\Models\TradeOffer;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\Attributes\Connection;
use Illuminate\Queue\Attributes\Queue;
use Illuminate\Queue\SerializesModels;
use Override;

#[Connection('redis')]
#[Queue('reverb')]
final class TradeOfferCreated implements ShouldBroadcast
{
    use Dispatchable;
    use SerializesModels;

    public User $receiver;

    /**
     * Create a new event instance.
     * @psalm-suppress PossiblyNullPropertyAssignmentValue
     */
    public function __construct(
        public TradeOffer $tradeOffer
    ) {
        $this->receiver = User::find($this->tradeOffer->receiver_id);
    }

    #[Override]
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('users.' . $this->receiver->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'trade-offer.created';
    }

    public function broadcastWith(): array
    {
        return [
            'tradeOffer' => $this->tradeOffer,
        ];
    }
}
