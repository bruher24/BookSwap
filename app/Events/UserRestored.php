<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class UserRestored implements ShouldQueue
{
    use Dispatchable;
    use SerializesModels;

    public string $connection = 'redis';

    public string $queue = 'listeners';

    /**
     * Create a new event instance.
     */
    public function __construct(
        public User $user
    )
    {
    }
}
