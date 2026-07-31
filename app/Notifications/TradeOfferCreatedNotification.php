<?php

namespace App\Notifications;

use App\Models\TradeOffer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TradeOfferCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public TradeOffer $tradeOffer,
        public User $sender
    ) {
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Сделка обновлена')
            ->view('mail.tradeOfferCreated', [
                'tradeOffer' => $this->tradeOffer,
                'receiver' => $notifiable,
                'sender' => $this->sender
            ])
            ->text('mail.tradeOfferCreated_text', [
                'tradeOffer' => $this->tradeOffer,
                'receiver' => $notifiable,
                'sender' => $this->sender
            ]);
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
        return 'notification.trade-offer.created';
    }

    private function payload(): array
    {
        return [
            'trade_offer_id' => $this->tradeOffer->id,
            'sender_id' => $this->tradeOffer->sender_id,
        ];
    }
}
