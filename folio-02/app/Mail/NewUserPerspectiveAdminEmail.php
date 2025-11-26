<?php

namespace App\Mail;

use App\Facades\AppConfig;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewUserPerspectiveAdminEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $totalActiveUsers;
    public $totalInActiveUsers;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $totalActiveUsers, $totalInActiveUsers)
    {
        $this->user = $user;
        $this->totalActiveUsers = $totalActiveUsers;
        $this->totalInActiveUsers = $totalInActiveUsers;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(AppConfig::get('FOLIO_CLIENT_NAME') . ' - New system user created')
            ->markdown('emails.new-user-email-to-perspective');
    }
}
