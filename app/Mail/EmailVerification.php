<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class EmailVerification extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user
    ) {
        //
    }

    /**
     * Get the message envelope.
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Verification',
        );
    }

    /**
     * Get the message content definition.
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.verifyYourEmail',
            text: 'mail.verifyYourEmail_text'
        );
    }

    /**
     * Get the attachments for the message.
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
