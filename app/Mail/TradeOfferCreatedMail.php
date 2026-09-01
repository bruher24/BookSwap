<?php

namespace App\Mail;

use App\Models\TradeOffer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class TradeOfferCreatedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public TradeOffer $tradeOffer,
        public User $receiver,
        public User $sender,
    ) {
        //
    }

    /**
     * Get the message envelope.
     * @psalm-suppress InvalidArgument
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->receiver->email,
            subject: 'Trade Offer Created',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.tradeOfferCreated',
            text: 'mail.tradeOfferCreated_text'
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
