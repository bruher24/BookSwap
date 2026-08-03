<?php

namespace App\Events;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Override;

final class BookForceDeleted implements ShouldBroadcast
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Book $book
    ) {
    }

    #[Override]
    public function broadcastOn(): array
    {
        return [
            new Channel('books'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'force-deleted';
    }

    public function broadcastWith(): array
    {
        return [
            'book' => new BookResource($this->book),
        ];
    }
}
