<?php

namespace App\Notifications\UserEvent;

use App\Helpers\AppHelper;
use App\Models\Lookups\UserTypeLookup;
use App\Models\UserEvents\UserEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class EventCancelled extends Notification
{
    use Queueable;

    public $event;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(UserEvent $event)
    {
        $this->event = $event;
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
        $event = $this->event;
        $organiser = $this->event->creator;

        $message = '<p>' . $organiser->full_name . ' [' . UserTypeLookup::getDescription($organiser->user_type) . '] has cancelled the following event.</p>';
        $message .= '<p><span class="text-info bolder">Event Title:  </span><a href="' . route('user_events.show', $event) . '">' . $event->title . '</a></p>';
        $message .= '<p><span class="text-info bolder">Event Start:  </span>' . Carbon::parse($event->start)->format('d/m/Y') . ' ' . Carbon::parse($event->start)->format('H:i') . '</p>';
        $message .= '<p><span class="text-info bolder">Event End:  </span>' . Carbon::parse($event->end)->format('d/m/Y') . ' ' . Carbon::parse($event->end)->format('H:i') . '</p>';
        $message .= '<p><span class="text-info bolder">Event Type:  </span>' . AppHelper::getUserEventsTypes($event->event_type) . '</p>';

        return [
            'organiser_id' => $organiser->id,
            'organiser_type' => $organiser->user_type,
            'event_id' => $event->id,
            'message' => $message,
            'title' => $this->title(),
        ];
    }

    private function title()
    {
        return 'Event cancelled by organiser.';
    }
}