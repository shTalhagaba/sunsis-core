<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LearnersNotLoggedInForFourWeeks extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $config;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($student, $config)
    {
        $this->student = $student;
        $this->config = $config;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Folio Account')->markdown('emails.learners-not-logged-in-for-4-weeks');
    }
}
