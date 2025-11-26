<?php

namespace App\Helpers;

use App\Models\LearningResources\LearningResource;
use App\Models\Organisations\Organisation;
use App\Models\Programmes\Programme;
use App\Models\Qualifications\Qualification;
use App\Models\Training\Otj;
use App\Models\Training\TrainingDeliveryPlanSession;
use App\Models\Training\TrainingDeliveryPlanSessionTask;
use App\Models\Training\TrainingRecord;
use App\Models\Training\TrainingRecordEvidence;
use App\Models\Training\TrainingRecordEvidenceAssessment;
use App\Models\Training\TrainingReview;
use App\Models\Training\TrainingReviewForm;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\PathGenerator\PathGenerator;
use Carbon\Carbon;

class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media) : string
    {
        if( $media->model instanceof Programme )
        {
            return 'programmes/' . $media->model->id . '/' . md5($media->id . config('app.key')) . '/';
        }
        elseif( $media->model instanceof Qualification )
        {
            return 'qualifications/' . $media->model->id . '/' . md5($media->id . config('app.key')) . '/';
        }
        elseif( $media->model instanceof Organisation )
        {
            return 'organisations/' . $media->model->id . '/' . md5($media->id . config('app.key')) . '/';
        }
        elseif( $media->model instanceof TrainingRecordEvidence )
        {
            $cutoffDate = new Carbon('2024-04-26');
            return $media->updated_at->lt($cutoffDate) ? 
                md5($media->id . config('app.key')) . '/' : 
                'trainings/' . $media->model->training_record->id . '/evidences/' . $media->model->id . '/' . md5($media->id . config('app.key')) . '/';
        }
        elseif( $media->model instanceof Otj )
        {
            $cutoffDate = new Carbon('2025-01-26');
            return $media->updated_at->lt($cutoffDate) ? 
                md5($media->id . config('app.key')) . '/' : 
                'trainings/' . $media->model->training->id . '/otj/' . $media->model->id . '/' . md5($media->id . config('app.key')) . '/';
        }
        elseif( $media->model instanceof TrainingReview )
        {
            return 'trainings/' . $media->model->training->id . '/reviews/' . $media->model->id . '/' . md5($media->id . config('app.key')) . '/';
        }
        elseif( $media->model instanceof TrainingReviewForm )
        {
            $review = $media->model->review;
            return 'trainings/' . $review->training->id . 
                '/reviews/' . $review->id . 
                '/forms/' . $media->model->id . '/' . md5($media->id . config('app.key')) . '/';
        }
        elseif( $media->model instanceof TrainingDeliveryPlanSession )
        {
            $cutoffDate = new Carbon('2025-01-26');
            return $media->updated_at->lt($cutoffDate) ? 
                md5($media->id . config('app.key')) . '/' : 
                'trainings/' . $media->model->training->id . '/delivery_plan_sessions/' . $media->model->id . '/' . md5($media->id . config('app.key')) . '/';
        }
        elseif( $media->model instanceof TrainingDeliveryPlanSessionTask )
        {
            $cutoffDate = new Carbon('2025-01-26');
            return $media->updated_at->lt($cutoffDate) ? 
                md5($media->id . config('app.key')) . '/' : 
                'trainings/' . $media->model->trainingRecord->id . '/' .  
                'delivery_plan_sessions/' . $media->model->session->id . '/' . 
                'tasks/' . $media->model->id . '/' . md5($media->id . config('app.key')) . '/';
        }
	elseif( $media->model instanceof TrainingRecordEvidenceAssessment )
        {
            return 'trainings/' . $media->model->training_record->id . 
                '/evidences/' . $media->model->evidence->id . 
                '/assessment_feedback/' . $media->model->id . '/' . md5($media->id . config('app.key')) . '/';
        }
        elseif( $media->model instanceof TrainingRecord )
        {
            return 'trainings/' . $media->model->id . '/' . md5($media->id . config('app.key')) . '/';
        }
	elseif( $media->model instanceof LearningResource )
        {
            return 'learning_resources/' . $media->model->id . '/' . md5($media->id . config('app.key')) . '/';
        }
        return md5($media->id . config('app.key')) . '/';
        // return $media->id . '/';
        // return config('app.storage_directory').'/' . $media->id . '/';
    }

    public function getPathForConversions(Media $media) : string
    {
        return $this->getPath($media).'conversions/';
    }

    public function getPathForResponsiveImages(Media $media) : string
    {
        return $this->getPath($media).'/cri/';
    }
}
