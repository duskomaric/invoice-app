<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $token
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Reset Your Password',
        );
    }

    public function content(): Content
    {
        $url = \Filament\Facades\Filament::getResetPasswordUrl($this->token, $this->user);

        return new Content(
            markdown: 'mail.reset-password',
            with: [
                'user' => $this->user,
                'url' => $url,
            ],
        );
    }
}
