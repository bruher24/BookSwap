<?php

namespace App\Events;

use App\Models\Author;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class AuthorForceDeleted implements ShouldQueue
{
    use Dispatchable;
    use SerializesModels;

    public string $connection = 'redis';

    public string $queue = 'listeners';

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Author $author
    ) {
    }
}
