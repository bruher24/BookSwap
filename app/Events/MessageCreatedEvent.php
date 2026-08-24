<?php

namespace App\Events;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\Attributes\Queue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;
use Override;

#[Queue('reverb')]
final class MessageCreatedEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public User $receiver;

    /**
     * Create a new event instance.
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullPropertyAssignmentValue
     * @psalm-suppress PossiblyNullReference
     */
    public function __construct(
        public Message $message
    ) {
        $this->receiver = $message->chat->users
            ->where('id', '!=', $message->sender_id)
            ->first();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    #[Override]
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chats.' . $this->message->chat_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.created';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => new MessageResource($this->message),
        ];
    }
}
