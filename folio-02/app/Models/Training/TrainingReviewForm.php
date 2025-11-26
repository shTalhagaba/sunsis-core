<?php

namespace App\Models\Training;

use App\Models\DocumentSignature;
use App\Models\Lookups\UserTypeLookup;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;



class TrainingReviewForm extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $table = 'training_review_forms';

    protected $guarded = [];

    public function review()
    {
    	return $this->belongsTo(TrainingReview::class, 'review_id');
    }

    public function signatures()
    {
        return $this->morphMany(DocumentSignature::class, 'model')
            ->latest('created_at');
    }

    public function assessorSignDate()
    {
        $assessorSign = $this->signatures()->where('signatory_system_user_type', UserTypeLookup::TYPE_ASSESSOR)->first();
        return !is_null($assessorSign) ? $assessorSign->created_at->format('d/m/Y H:i:s') : '';
    }

    public function learnerSignDate()
    {
        $learnerSign = $this->signatures()->where('signatory_system_user_type', UserTypeLookup::TYPE_STUDENT)->first();
        return !is_null($learnerSign) ? $learnerSign->created_at->format('d/m/Y H:i:s') : '';
    }

    public function employerSignDate()
    {
        $employerSign = $this->signatures()->where('signatory_system_user_type', UserTypeLookup::TYPE_EMPLOYER_USER)->first();
        return !is_null($employerSign) ? $employerSign->created_at->format('d/m/Y H:i:s') : '';
    }

    public function locked()
    {
        return $this->assessor_signed;// && $this->learner_signed;
    }
    
    public function readyForLearnerSign()
    {
        return $this->assessor_signed && ! $this->learner_signed;
    }

    public function readyForEmployerSign()
    {
        return $this->assessor_signed && $this->learner_signed && ! $this->employer_signed;
    }

    public function completed()
    {
        return $this->assessor_signed && $this->learner_signed && $this->employer_signed;
    }

    public static function boot()
    {
        parent::boot();

        self::deleting(function ($form) {
            $form->media()->each(function ($media) {
                $media->delete();
            });
        });
    }
}
