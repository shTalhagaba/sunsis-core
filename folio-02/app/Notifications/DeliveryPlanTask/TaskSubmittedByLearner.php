<?php

namespace App\Notifications\DeliveryPlanTask;

use App\Models\Training\TrainingDeliveryPlanSessionTask;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class TaskSubmittedByLearner extends Notification
{
    use Queueable;

    public $task;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TrainingDeliveryPlanSessionTask $task)
    {
        $this->task = $task;
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
        $task = $this->task;
        $training = $task->trainingRecord;

        $message = '<p>A delivery plan session task has been submitted by the learner, following are the details.</p>';
        $message .= '<p><span class="text-info bolder">Learner Name:  </span><a href="' . route('trainings.show', $training) . '">' . $training->student->full_name . '</a></p>';
        $message .= '<p><span class="text-info bolder">Programme:  </span>' . $training->programme->title . '</p>';
        $message .= '<p><span class="text-info bolder">Task Title:  </span>' . $task->title . '</p>';
        $message .= '<p><span class="text-info bolder">Task Submitted Date:  </span>' . optional($task->learner_signed_datetime)->format('d/m/Y H:i:s') . '</p>';

        return [
            'created_by' => $training->student_id,
            'task_id' => $task->id,
            'message' => $message,
            'title' => $this->title(),
        ];
    }

    private function title()
    {
        return 'Delivery plan session task is submitted by learner.';
    }
}
