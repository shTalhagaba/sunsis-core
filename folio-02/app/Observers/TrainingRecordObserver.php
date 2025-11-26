<?php

namespace App\Observers;

use App\Models\Training\TrainingRecord;
use Carbon\Carbon;

class TrainingRecordObserver
{
    public function created(TrainingRecord $training)
    {
        // create training reviews if not created.
        if ($training->reviews->count() == 0) 
        {
            // do not create if the values are not set for the programme.
            if(is_null($training->programme->first_review) || $training->programme->first_review == 0)
            {
                return;
            }
            if(is_null($training->programme->review_frequency) || $training->programme->review_frequency == 0)
            {
                return;
            }

            $reviewNumber = 1;
            $startDate = Carbon::parse($training->getOriginal('start_date'));
            $endDate = Carbon::parse($training->getOriginal('planned_end_date'));
            $firstReviewWeeks = (is_null($training->programme->first_review) || $training->programme->first_review == 0) ? 4 : $training->programme->first_review;
            $subsequentWeeks = (is_null($training->programme->review_frequency) || $training->programme->review_frequency == 0) ? 4 : $training->programme->review_frequency;
    
            while ($startDate->lessThanOrEqualTo($endDate)) 
            {
                if ($reviewNumber == 1)
                {
                    $startDate->addWeeks($firstReviewWeeks);
                }
                else
                {
                    $startDate->addWeeks($subsequentWeeks);
                }

                if ($startDate->greaterThan($endDate))
                    break;

                $training->reviews()->create([
                    'due_date' => $startDate->format('Y-m-d'),
                ]);

                $reviewNumber++;
            }
        }
    }

    public function deleting(TrainingRecord $training)
    {
        $training->portfolios()->each(function ($portfolio) {
            $portfolio->delete();
        });

        $training->media()->each(function ($media) {
            $media->delete();
        });

        $training->evidences()->each(function ($evidence) {
            $evidence->delete();
        });

        $training->training_plans()->each(function ($trainingPlan) {
            $trainingPlan->delete();
        });

        $training->sessions()->each(function ($session) {
            $session->delete();
        });

        $training->otj()->each(function ($otj) {
            $otj->delete();
        });

        $training->reviews()->each(function ($review) {
            $review->delete();
        });

        $training->crmNotes()->each(function ($review) {
            $review->delete();
        });
    }
}
