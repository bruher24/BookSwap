<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Override;

final class EmailVerifiedEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public User $user,
    ) {
    }

    #[Override]
    public function broadcastOn(): array
    {
        return [
            new Channel('system'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'email-verified';
    }
}
