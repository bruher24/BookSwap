<?php

namespace App\Events;

use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Override;

final class AuthorCreatedEvent implements ShouldBroadcast
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Author $author
    ) {
    }

    #[Override]
    public function broadcastOn(): array
    {
        return [
            new Channel('authors'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'created';
    }

    public function broadcastWith(): array
    {
        return [
            'author' => new AuthorResource($this->author),
        ];
    }
}
