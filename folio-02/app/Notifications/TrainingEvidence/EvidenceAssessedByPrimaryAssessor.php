<?php

namespace App\Notifications\TrainingEvidence;

use App\Models\Training\TrainingRecordEvidence;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class EvidenceAssessedByPrimaryAssessor extends Notification
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

        $message = '<p>An evidence has been assessed by ' . optional($training->primaryAssessor)->full_name . ', who is primary assessor of the following learner. Here are the details.</p>';
        $message .= '<p><span class="text-info bolder">Learner Name:  </span><a href="' . route('trainings.show', $training) . '">' . $training->student->full_name . '</a></p>';
        $message .= '<p><span class="text-info bolder">Programme:  </span>' . $training->programme->title . '</p>';
        $message .= '<p><span class="text-info bolder">Evidence Name:  </span>' . $evidence->evidence_name . '</p>';
        $message .= '<p><span class="text-info bolder">Evidence Status:  </span>' . $evidence->evidence_status . '</p>';
        $message .= '<p><span class="text-info bolder">Updated Date:  </span>' . Carbon::parse($evidence->updated_at)->format('d/m/Y') . ' ' . Carbon::parse($evidence->updated_at)->format('H:i') . '</p>';

        return [
            'evidence_id' => $evidence->id,
            'title' => $this->title(),
            'message' => $message,
        ];
    }

    private function title()
    {
        return 'Evidence assessed by primary assessor';
    }
}
