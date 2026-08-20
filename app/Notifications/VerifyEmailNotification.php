<?php

namespace App\Notifications;

use App\Mail\VerifyEmailMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

final class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
    }

    public function via(User $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail(User $notifiable): Mailable
    {
        return new VerifyEmailMail($notifiable);
    }

    public function toBroadcast(User $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->payload($notifiable));
    }

    public function toArray(User $notifiable): array
    {
        return $this->payload($notifiable);
    }

    public function viaQueues(): array
    {
        return [
            'mail' => 'mail',
            'broadcast' => 'reverb',
            'database' => 'listeners',
        ];
    }

    public function broadcastType(): string
    {
        return 'notification.user.created';
    }

    private function payload(User $user): array
    {
        return [
            'user_id' => $user->id,
            'email' => $user->email,
            'created_at' => $user->created_at,
        ];
    }
}
