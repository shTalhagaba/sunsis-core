<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserPerspectiveAdminEmail;

class NewUserNotificationToSupportListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to('support@perspective-uk.com')
            ->send(
                new NewUserPerspectiveAdminEmail(
                    $event->user, 
                    $event->totalActiveUsers, 
                    $event->totalInActiveUsers
                )
            );
    }
}
