<?php

namespace App\Notifications\StaffDevelopment;

use App\Models\StaffDevelopment\StaffDevelopmentSupport;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class StaffDevelopmentSupportFormSubmitted extends Notification
{
    use Queueable;

    public $form;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(StaffDevelopmentSupport $form)
    {
        $this->form = $form;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        //return (new MailMessage)->markdown('notifications.user_event.invited');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $form = $this->form;

        $message = '<p>A <a href="' . route('staff_development_support.show', ['staff_development_support' => $form]) . '">Staff Development Support Form</a> has been submitted. You are required to complete your part and sign, following are the details.</p>';
        $message .= '<p><span class="text-info bolder">Support provided by:  </span>' . $form->supportFrom->full_name . '</p>';
        $message .= '<p><span class="text-info bolder">Support type:  </span>' . $form->support_type . '</p>';
        $message .= '<p><span class="text-info bolder">Date:  </span>' . $form->provision_date->format('d/m/Y') . '</p>';

        return [
            'created_by' => $form->support_from_id,
            'staff_development_support_id' => $form->id,
            'message' => $message,
            'title' => 'Staff Support Development - ' . $form->support_type,
        ];
    }
}
