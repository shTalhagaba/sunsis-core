<?php

namespace App\Notifications\TrainingIQA;

use App\Models\Training\PortfolioUnit;
use App\Models\Training\PortfolioUnitIqa;
use App\Models\Training\TrainingRecord;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class PortfolioUnitIQAd extends Notification
{
    use Queueable;

    public $unit;
    public $training;
    public $iqaPersonnel;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TrainingRecord $training, PortfolioUnit $unit, User $iqaPersonnel)
    {
        $this->training = $training;
        $this->unit = $unit;
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
        $unit = $this->unit;
        $training = $this->training;

        $message = '<p>IQA has assessed the following unit, following are the details.</p>';
        $message .= '<p><span class="text-info bolder">IQA Personnel:  </span>' . $this->iqaPersonnel->full_name . '</p>';
        $message .= '<p><span class="text-info bolder">Learner Name:  </span><a href="' . route('trainings.show', $training) . '">' . $training->student->full_name . '</a></p>';
        $message .= '<p><span class="text-info bolder">Programme:  </span>' . $training->programme->title . '</p>';
        $message .= '<p><span class="text-info bolder">Unit:  </span>[' . $unit->unique_ref_number . '] [' . $unit->unit_owner_ref . '] ' . $unit->title . '</p>';
        $message .= '<p><span class="text-info bolder">IQA Status:  </span>' . ($unit->iqa_status == PortfolioUnitIqa::STATUS_IQA_ACCEPTED ? 'IQA Accepted' : ($unit->iqa_status == PortfolioUnitIqa::STATUS_IQA_REFERRED ? 'Referred by IQA' : '')) . '</p>';

        return [
            'iqa_user' => $this->iqaPersonnel->id,
            'unit_id' => $unit->id,
            'message' => $message,
            'title' => $this->title(),
        ];
    }

    private function title()
    {
        return 'Portfolio unit assessed by IQA/Verifier';
    }
}
