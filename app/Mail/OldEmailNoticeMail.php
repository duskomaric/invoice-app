<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OldEmailNoticeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $newEmail, public string $blockUrl) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Email Change Request Notice',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.old-email-notice',
            with: [
                'newEmail' => $this->newEmail,
                'blockUrl' => $this->blockUrl,
            ],
        );
    }
}
