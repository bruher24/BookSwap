<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class AuthorCreated implements ShouldQueue
{
    use Dispatchable;
    use SerializesModels;

    public string $connection = 'rabbitmq';

    public string $queue = 'events';

    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $author
    ) {
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('test-channel');
    }
}
