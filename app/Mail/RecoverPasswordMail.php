<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecoverPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    private string $password;
    /**
     * Create a new message instance.
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                env('MAIL_FROM_ADDRESS', 'anchilogistics@gmail.com'),
                env('MAIL_FROM_NAME', 'anchilogistics@gmail.com')
            ),
            subject: 'Recover Password Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.recover_password',
            with: [
                'password' => $this->password
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
