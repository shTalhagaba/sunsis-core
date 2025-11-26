<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReviewFormMailToEmployer extends Mailable
{
    use Queueable, SerializesModels;

    public $signedUrl;
    public $employerUser;
    public $review;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($signedUrl, $employerUser, $review)
    {
        $this->signedUrl = $signedUrl;
        $this->employerUser = $employerUser;
        $this->review = $review;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Review Form Sign Email')
            ->markdown('emails.review-form-email-to-employer', ['actionText' => 'Review Form', 'actionURL' => $this->signedUrl]);
    }
}
