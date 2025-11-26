<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewMessageEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $recipient;
    public $sender;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($recipient, $sender)
    {
        $this->recipient = $recipient;
        $this->sender = $sender;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Folio Message')->markdown('emails.new-message');
    }
}
