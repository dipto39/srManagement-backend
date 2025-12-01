<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $filePath;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $filePath)
    {
        $this->user = $user;
        $this->filePath = $filePath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'User Welcome Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
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
        // Attach the file 

        // if (!$this->filePath) {
        //     return [];
        // }

        // return [
        //     Attachment::fromPath($this->filePath)
        //         ->as('welcome-image.png')
        //         ->withMime('image/png'),
        // ];
    }
}
