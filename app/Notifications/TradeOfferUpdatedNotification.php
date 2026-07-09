<?php

namespace App\Notifications;

use App\Models\TradeOffer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TradeOfferUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public TradeOffer $tradeOffer,
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
            ->view('mail.tradeOfferUpdated', [
                'tradeOffer' => $this->tradeOffer,
                'receiver' => $notifiable,
            ])
            ->text('mail.tradeOfferUpdated_text', [
                'tradeOffer' => $this->tradeOffer,
                'receiver' => $notifiable,
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
        return 'notification.trade-offer.updated';
    }

    private function payload(): array
    {
        return [
            'trade_offer_id' => $this->tradeOffer->id,
            'sender_id' => $this->tradeOffer->sender_id,
            'receiver_id' => $this->tradeOffer->receiver_id,
            'status' => $this->tradeOffer->status->value,
            'status_label' => $this->tradeOffer->status->label(),
        ];
    }
}
