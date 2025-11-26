<?php

namespace App\Notifications\Otj;

use App\Models\Training\Otj;
use App\Models\Training\TrainingRecord;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OtjLogCreated extends Notification
{
    use Queueable;

    public $training;
    public $otj;
    public $deliveryPlanSessionId;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TrainingRecord $training, Otj $otj, $deliveryPlanSessionId = null)
    {
        $this->training = $training;
        $this->otj = $otj;
        $this->deliveryPlanSessionId = $deliveryPlanSessionId;
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
        $otj = $this->otj;
        $training = $this->training;
        $deliveryPlanSessionId = $this->deliveryPlanSessionId;

        $message = '<p>Please complete and sign an OTJ log entry as part of delivery plan session.</p>';
        $message .= '<p><span class="text-info bolder">Title:  </span>' . $otj->title . '</p>';
        $message .= '<p><span class="text-info bolder">Date:  </span>' . optional(Carbon::parse($otj->date))->format('d/m/Y') . '</p>';
        $message .= '<p><span class="text-info bolder">Date:  </span>' . optional(Carbon::parse($otj->start_time))->format('H:i') . '</p>';
        $message .= '<p><a class="btn btn-xs btn-info btn-round" href="' . route('trainings.otj.show', [$training, $otj]) . '">Click to Open</a></p>';

        return [
            'tr_id' => $training->id,
            'otj_id' => $otj->id,
            'dp_session_id' => $deliveryPlanSessionId,
            'message' => $message,
            'title' => "OTJH log entry needs submission",
        ];
    }
}