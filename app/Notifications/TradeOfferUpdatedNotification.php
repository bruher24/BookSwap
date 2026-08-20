<?php

namespace App\Notifications;

use App\Mail\TradeOfferUpdatedMail;
use App\Models\TradeOffer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

final class TradeOfferUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public TradeOffer $tradeOffer,
    ) {
        $this->afterCommit();
    }

    public function via(User $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail(User $notifiable): Mailable
    {
        return new TradeOfferUpdatedMail($this->tradeOffer, $notifiable);
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
