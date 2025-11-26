<?php

namespace App\Notifications\TrainingEvidence;

use App\Models\Training\TrainingRecordEvidence;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class EvidenceSubmitted extends Notification
{
    use Queueable;

    public $evidence;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TrainingRecordEvidence $evidence)
    {
        $this->evidence = $evidence;
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
        $evidence = $this->evidence;
        $training = $evidence->training_record;

        $message = '<p>A new evidence has been submitted, following are the details.</p>';
        $message .= '<p><span class="text-info bolder">Learner Name:  </span><a href="' . route('trainings.show', $training) . '">' . $training->student->full_name . '</a></p>';
        $message .= '<p><span class="text-info bolder">Programme:  </span>' . $training->programme->title . '</p>';
        $message .= '<p><span class="text-info bolder">Evidence Name:  </span>' . $evidence->evidence_name . '</p>';
        $message .= '<p><span class="text-info bolder">Evidence Submitted By:  </span>' . optional(User::find($evidence->created_by))->full_name . '</p>';
        $message .= '<p><span class="text-info bolder">Evidence Submitted Date:  </span>' . Carbon::parse($evidence->created_at)->format('d/m/Y') . ' ' . Carbon::parse($evidence->created_at)->format('H:i') . '</p>';

        return [
            'created_by' => $evidence->created_by,
            'evidence_id' => $evidence->id,
            'message' => $message,
            'title' => $this->title(),
        ];
    }

    private function title()
    {
        return 'Evidence submitted by learner.';
    }
}
