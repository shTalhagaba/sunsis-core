<?php

namespace App\Notifications\TrainingEvidence;

use App\Models\Training\TrainingRecordEvidence;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class EvidenceIQAd extends Notification
{
    use Queueable;

    public $evidence;
    public $iqaPersonnel;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TrainingRecordEvidence $evidence, User $iqaPersonnel)
    {
        $this->evidence = $evidence;
        $this->iqaPersonnel = $iqaPersonnel;
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

        $message = '<p>Your assessed work has been IQA assessed, following are the details.</p>';
        $message .= '<p><span class="text-info bolder">IQA Personnel:  </span>' . $this->iqaPersonnel->full_name . '</p>';
        $message .= '<p><span class="text-info bolder">Learner Name:  </span><a href="' . route('trainings.show', $training) . '">' . $training->student->full_name . '</a></p>';
        $message .= '<p><span class="text-info bolder">Programme:  </span>' . $training->programme->title . '</p>';
        $message .= '<p><span class="text-info bolder">Evidence Name:  </span>' . $evidence->evidence_name . '</p>';
        $message .= '<p><span class="text-info bolder">IQA Status:  </span>' . ($evidence->iqa_status == 1 ? 'Accepted by IQA' : 'Rejected by IQA') . '</p>';

        return [
            'created_by' => $evidence->created_by,
            'evidence_id' => $evidence->id,
            'message' => $message,
            'title' => $this->title(),
        ];
    }

    private function title()
    {
        return 'Evidence IQA\'d by IQA/Verifier';
    }
}
