<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Message $message,
    ) {
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        // TODO: добавить mailable
        return (new MailMessage())
            ->subject('Новое сообщение')
            ->line('Вы получили новое сообщение.')
            ->line($this->message->body);
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->payload());
    }

    public function toArray(object $notifiable): array
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
