<?php

namespace App\Notifications\TrainingReview;

use App\Models\Training\TrainingReview;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class ReviewSubmittedByAssessor extends Notification
{
    use Queueable;

    public $review;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TrainingReview $review)
    {
        $this->review = $review;
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
        $review = $this->review;
        $training = $review->training;

        $message = '<p>A review has been completed by your assessor, please complete and sign the form if you have not already signed. Following are the details.</p>';
        $message .= '<p><span class="text-info bolder">Review:  </span><a href="' . route('trainings.reviews.form.show', ['training' => $training, 'review' => $review]) . '">Review Form - ' . $review->title . '</a></p>';
        $message .= '<p><span class="text-info bolder">Assessor:  </span>' . optional($training->primaryAssessor)->full_name . '</p>';
        $message .= '<p><span class="text-info bolder">Review Title:  </span>' . $review->title . '</p>';
        $message .= '<p><span class="text-info bolder">Review Completed Date:  </span>' . $review->assessor_signed_at . '</p>';

        return [
            'created_by' => $training->primary_assessor,
            'review_id' => $review->id,
            'message' => $message,
            'title' => $this->title(),
        ];
    }

    private function title()
    {
        return 'Review is signed by your assessor.';
    }
}
