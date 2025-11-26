<?php

namespace App\Listeners;

use App\Facades\AppConfig;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\NewUserEmail;
use App\Mail\NewUserPassword;
use App\Mail\NewUserPerspectiveAdminEmail;
use Illuminate\Support\Facades\Mail;


class NewUserLoginDetailsListener
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // email login details to the user
        Mail::to($event->user->primary_email)
          ->send(new NewUserEmail($event->user));

        Mail::to($event->user->primary_email)
             ->later(now()->addMinutes(1), new NewUserPassword($event->user, $event->password));
    }
}
