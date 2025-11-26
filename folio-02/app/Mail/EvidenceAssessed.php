<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EvidenceAssessed extends Mailable
{
    use Queueable, SerializesModels;

    public $evidence;
    public $student;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($evidence, $student)
    {
        $this->evidence = $evidence;
        $this->student = $student;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Folio Feedback')->markdown('emails.tr-evidence-assessed');
    }
}
