<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class VerifyYourEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public string $unsubscribeUrl,
        public string $messageId
    ) {
    }

    public function headers(): Headers
    {
        $unsubscribeLink = "<{$this->unsubscribeUrl}?message_id={$this->messageId}>";
        $unsubscribeEmail = "<mailto:unsubscribe@example.com?subject=unsubscribe&message_id={$this->messageId}>";

        return new Headers(
            text: [
                'List-Unsubscribe' => $unsubscribeLink . ', ' . $unsubscribeEmail,
            ],
        );
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify your email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.verifyYourEmail',
            //            text: 'mail.test-text'
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
