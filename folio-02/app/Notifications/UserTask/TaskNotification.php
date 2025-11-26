<?php

namespace App\Notifications\UserTask;

use App\Helpers\AppHelper;
use App\Models\Lookups\UserTypeLookup;
use App\Models\UserEvents\UserEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class TaskNotification extends Notification
{
    use Queueable;

    public $userTask;
    public $actionType;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(UserEvent $userTask, string $actionType = 'assigned')
    {
        $this->userTask = $userTask;
        $this->actionType = $actionType;
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
        //return (new MailMessage)->markdown('notifications.user_event.accepted');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $title = '';
        $message = '';
        $event = $this->userTask;

        switch ($this->actionType) {

            case 'updated':
                $title = $event->title . "  has been updated by " . $event->creator->full_name;
                $message = '<p>The following task has been updated:</p>'
                    . '<p><strong>' . \App\Helpers\AppHelper::getUserTaskTypes($event->task_type) . '</strong></p>'
                    . '<p><span class="text-info bolder">Start:</span> ' . Carbon::parse($event->start)->format('d/m/Y H:i') . '</p>'
                    . '<p><span class="text-info bolder">End:</span> ' . Carbon::parse($event->end)->format('d/m/Y H:i') . '</p>';
                break;

            case 'completed':
                $title = $event->title . " Task completed by " . $event->assignedIqa->full_name;
                $message = '<p>The following task was marked as <strong>Completed</strong>:</p>'
                    . '<p><strong>' . \App\Helpers\AppHelper::getUserTaskTypes($event->task_type) . '</strong></p>'
                    . '<p><span class="text-info bolder">Start:</span> ' . Carbon::parse($event->start)->format('d/m/Y H:i') . '</p>'
                    . '<p><span class="text-info bolder">End:</span> ' . Carbon::parse($event->end)->format('d/m/Y H:i') . '</p>';
                break;

            case 'signed_off':
                $title = "Your " . $event->title . " Task signed off by " . $event->creator->full_name;
                $message = '<p>The task has been <strong>signed off</strong>.</p>'
                    . '<p><strong>' . \App\Helpers\AppHelper::getUserTaskTypes($event->task_type) . '</strong></p>'
                    . '<p><span class="text-info bolder">Start:</span> ' . Carbon::parse($event->start)->format('d/m/Y H:i') . '</p>'
                    . '<p><span class="text-info bolder">End:</span> ' . Carbon::parse($event->end)->format('d/m/Y H:i') . '</p>';
                break;

            case 'assigned':
            default:
                $title = "You have been assigned to a " . $event->title . " task";
                $message = '<p>You have been assigned to a new task </p>'
                    . '<p><strong>' . \App\Helpers\AppHelper::getUserTaskTypes($event->task_type) . '</strong></p>'
                    . '<p><span class="text-info bolder">Start:</span> ' . Carbon::parse($event->start)->format('d/m/Y H:i') . '</p>'
                    . '<p><span class="text-info bolder">End:</span> ' . Carbon::parse($event->end)->format('d/m/Y H:i') . '</p>';
                break;
        }

        return [
            'task_id' => $event->id,
            'title' => $title,
            'start' => $event->start,
            'end' => $event->end,
            'type' => $event->type,
            'message' => $message,
        ];
    }

    private function title()
    {
        return 'Task assigned by Quality Manager.';
    }
}