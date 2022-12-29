<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UnbanAppealMail extends Mailable
{
    use Queueable, SerializesModels;
    public $details;

    /**
    * Create a new message instance.
    *
    * @return void
    */
    public function __construct($details)
    {
        $this->details = $details;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Unban Appeal Notification')
            ->view('mail.unban-appeal-email');
    }
}
