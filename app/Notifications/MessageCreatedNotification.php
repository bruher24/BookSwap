<?php

namespace App\Notifications;

use App\Mail\MessageReceived;
use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class MessageCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Message $message,
        public User $sender,
    ) {
        $this->afterCommit();
    }

    public function via(User $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail(User $notifiable): Mailable
    {
        return new MessageReceived($this->message, $notifiable, $this->sender);
    }

    public function toBroadcast(User $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->payload());
    }

    public function toArray(User $notifiable): array
    {
        return $this->payload();
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
        return 'notification.message.created';
    }

    private function payload(): array
    {
        return [
            'message_id' => $this->message->id,
            'chat_id' => $this->message->chat_id,
            'sender_id' => $this->message->sender_id,
            'body' => $this->message->body,
        ];
    }
}
