<?php

namespace App\Notifications\DeliveryPlanTask;

use App\Models\Training\TrainingDeliveryPlanSessionTask;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class TaskCreatedForLearner extends Notification
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
        $session = $task->session;
        $url = route('trainings.sessions.tasks.show', [$training, $session, $task]);

        $message = '<p>A new delivery plan session task has been created for you, following are the details.</p>';
        $message .= '<p><span class="text-info bolder">Learner Name:  </span><a href="' . route('trainings.show', $training) . '">' . $training->student->full_name . '</a></p>';
        $message .= '<p><span class="text-info bolder">Programme:  </span>' . $training->programme->title . '</p>';
        $message .= '<p><span class="text-info bolder">Task Title:  </span><a href="' . $url . '">' . $task->title . '</a></p>';
        $message .= '<p><span class="text-info bolder">Task Created Date:  </span>' . optional($task->created_at)->format('d/m/Y H:i:s') . '</p>';

        return [
            'created_by' => $task->created_by,
            'task_id' => $task->id,
            'message' => $message,
            'title' => $this->title(),
        ];
    }

    private function title()
    {
        return 'A new task is created for you.';
    }
}
