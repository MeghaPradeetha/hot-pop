<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserAdded extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $userEmail;
    public $userPassword;
    /**
     * Create a new message instance.
     */
    public function __construct($userName,$userEmail,$userPassword)
    {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userPassword = $userPassword;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'User Added',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            
            view: 'emails.user-added',
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
