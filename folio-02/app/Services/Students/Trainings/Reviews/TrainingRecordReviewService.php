<?php

namespace App\Services\Students\Trainings\Reviews;

use App\Helpers\AppHelper;
use App\Models\DocumentSignature;
use App\Models\Lookups\TrainingReviewLookup;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Training\TrainingRecord;
use App\Models\Training\TrainingReview;
use App\Models\Training\TrainingReviewForm;
use App\Notifications\TrainingReview\ReviewSubmittedByAssessor;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;

class TrainingRecordReviewService
{
    public function create(TrainingRecord $trainingRecord, array $reviewData)
    {
        return $trainingRecord->reviews()->create($reviewData);
    }

    public function update(TrainingRecord $trainingRecord, TrainingReview $review, array $reviewData)
    {
        return $review->update($reviewData);
    }

    public function delete(TrainingRecord $trainingRecord, TrainingReview $review)
    {
        return $review->delete();
    }

    public function updateForm(
        TrainingRecord $trainingRecord,
        TrainingReview $review,
        TrainingReviewForm $form,
        array $formData,
        $userType
    ) {
        if ($userType === UserTypeLookup::TYPE_ASSESSOR) {
            $review->update([
                'meeting_date' => $formData['meeting_date'],
            ]);
            $form->update([
                'form_data' => json_encode(Arr::except($formData, ['_method', '_token', 'assessor_signed', 'learner_signed', 'employer_signed'])),
                'assessor_signed' => isset($formData['assessor_signed']) ? $formData['assessor_signed'] : 0,
                'assessor_signed_at' => isset($formData['assessor_signed']) ? now()->format('Y-m-d H:i:s') : null,
            ]);
            if ($form->assessor_signed) {
                DocumentSignature::logSignatures($form);
                $trainingRecord->student->notify(new ReviewSubmittedByAssessor($review));
                AppHelper::cacheUnreadCountForUser($trainingRecord->student);
            }
        }

        if ($userType === UserTypeLookup::TYPE_STUDENT) {
            $formDetail = (array) json_decode($form->form_data);
            $formDetail['learner_comments'] = $formData['learner_comments'];
            $form->update([
                'form_data' => json_encode($formDetail),
                'learner_signed' => isset($formData['learner_signed']) ? $formData['learner_signed'] : 0,
                'learner_signed_at' => isset($formData['learner_signed']) ? now()->format('Y-m-d H:i:s') : null,
            ]);
            if ($form->learner_signed) {
                DocumentSignature::logSignatures($form);
            }
        }

        if ($userType == UserTypeLookup::TYPE_EMPLOYER_USER) {
            $formDetail = (array) json_decode($form->form_data);
            $formDetail['employer_comments'] = $formData['employer_comments'];
            $form->update([
                'form_data' => json_encode($formDetail),
                'employer_signed' => isset($formData['employer_signed']) ? $formData['employer_signed'] : 0,
                'employer_signed_at' => isset($formData['employer_signed']) ? now()->format('Y-m-d H:i:s') : null,
            ]);
            if ($form->employer_signed && auth()->check()) {
                DocumentSignature::logSignatures($form);
            }
        }

        return $form;
    }

    public function generateSignedUrl(TrainingReview $review, $user)
    {
        return URL::temporarySignedRoute(
            'reviews.showSignatureForm',
            now()->addHours(24),
            [
                'training_id' => $review->training->id,
                'review_id' => $review->id,
                'form_id' => $review->form->id,
                'user_id' => $user->id
            ]
        );
    }

    public function generateBlankReviews(TrainingRecord $trainingRecord, $firstReview = null, $reviewFrequency = null)
    {
        $programme = $trainingRecord->programme;
        $firstReview = is_null($firstReview) ? $programme->first_review : $firstReview;
        $reviewFrequency = is_null($reviewFrequency) ? $programme->review_frequency : $reviewFrequency;

        if (is_null($firstReview) || is_null($reviewFrequency)) {
            return false;
        }

        $startDate = $trainingRecord->start_date;
        $endDate = $trainingRecord->planned_end_date;
        $reviewDate = $startDate->copy()->addWeeks($firstReview);

        while ($reviewDate->lessThanOrEqualTo($endDate)) {
            $trainingRecord->reviews()->create([
                'title' => 'Progress Review',
                'due_date' => $reviewDate->format('Y-m-d'),
                'type_of_review' => TrainingReviewLookup::TYPE_PROGRESS_REVIEW,
                'assessor' => $trainingRecord->primary_assessor,
            ]);
            $reviewDate->addWeeks($reviewFrequency);
        }

        return true;
    }
}
