<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class MailModel extends Mailable
{

    public $mailData;

    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    public function envelope()
    {
        return new Envelope(
            from: new Address('Admin@TaskSquad.net', 'TaskSquad'),
            subject: 'Welcome to TaskSquad ',
        );
    }

    public function content()
    {
        return new Content(
            view: 'pages.emails.email',
        );
    }
}
