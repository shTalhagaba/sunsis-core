<?php

namespace App\Models\Training;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class TrainingRecordEvidenceAssessment extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $table = 'tr_evidence_assesments';

    protected $guarded = [];

    public function training_record()
    {
    	return $this->belongsTo(TrainingRecord::class, 'tr_id');
    }

    public function evidence()
    {
    	return $this->belongsTo(TrainingRecordEvidence::class, 'evidence_id');
    }

    public function assessedBy()
    {
        return $this->belongsTo(User::class, 'assessment_by');
    }

    public function statusDescription()
    {
        switch($this->assessment_status)
        {
            case self::STATUS_ASSESSOR_ACCEPTED:
                return 'Assessor Accepted';
            case self::STATUS_ASSESSOR_REJECTED:
                return 'Assessor Rejected';
            case self::STATUS_IQA_ACCEPTED:
                return 'IQA Accepted';
            case self::STATUS_IQA_REJECTED:
                return 'IQA Rejected';
            default:
                return $this->assessment_status;
        }
    }

    public function typeDescription()
    {
        return $this->assessment_by == 'A' ? 'Assessor' : (
            $this->assessment_by == 'V' ? 'Verifier' : $this->assessment_by
        );
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($otj) {
            $otj->media()->each(function ($media) {
                $media->delete();
            });
        });
    }

    const STATUS_ASSESSOR_ACCEPTED = 2;
    const STATUS_ASSESSOR_REJECTED = 3;
    const STATUS_IQA_ACCEPTED = 5;
    const STATUS_IQA_REJECTED = 6;

    const ASSESSMENT_BY_ASSESSOR = 'A';
    const ASSESSMENT_BY_IQA = 'I';
    const ASSESSMENT_BY_EQA = 'E';
} 
