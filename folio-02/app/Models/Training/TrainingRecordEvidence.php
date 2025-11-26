<?php

namespace App\Models\Training;

use App\Models\LookupManager;
use App\Models\Lookups\TrainingEvidenceCategoryLookup;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use OwenIt\Auditing\Contracts\Auditable;

class TrainingRecordEvidence extends Model implements HasMedia, Auditable
{
    use HasMediaTrait, \OwenIt\Auditing\Auditable, Filterable;

    protected $table = 'tr_evidences';

    protected $guarded = [];

    public function training_record()
    {
    	return $this->belongsTo(TrainingRecord::class, 'tr_id');
    }

    public function mapped_pcs()
    {
        return $this->belongsToMany(PortfolioPC::class, 'pc_evidence_mappings', 'tr_evidence_id', 'portfolio_pc_id')
            ->orderBy('pc_sequence', 'ASC')
            ->withPivot('created_by');
    }

    public function submissions()
    {
        return $this->hasMany(TrainingRecordEvidence::class, 'parent_id');
    }

    public function categories()
    {
        return $this->belongsToMany(
            TrainingEvidenceCategoryLookup::class, 
            'tr_evidence_categories',
            'evidence_id',
            'category_id',
        )->withTimestamps();
    }

    public function original()
    {
        return $this->belongsTo(TrainingRecordEvidence::class, 'parent_id');
    }

    public function mappings()
    {
        return $this->hasMany(PCEvidenceMapping::class, 'tr_evidence_id');
    }

    public function assessments()
    {
        return $this->hasMany(TrainingRecordEvidenceAssessment::class, 'evidence_id');
    }

    public function latestAssessment()
    {
        return $this->hasOne(TrainingRecordEvidenceAssessment::class, 'evidence_id')->latest();
    }

    public function notifications()
    {
        return $this->morphMany(\App\Models\Notification::class, 'notifiable');
    }

    public static function getDDLEvidenceAssessmentMethods($blank = true)
    {
        $methods = LookupManager::getEvidenceAssessmentMethod();
        return $blank ? ['' => ''] + $methods : $methods;
    }

    public function getAssessmentMethodAttribute($value)
    {
        return $value == '' ? '' : LookupManager::getEvidenceAssessmentMethod($value);
    }

    public static function getAssessmentStatusDDL()
    {
        return [
            2 => 'Assessor Accepted',
            3 => 'Assessor Rejected'
          ];
    }

    public static function getIqaStatusDDL()
    {
        return [
            5 => 'IQA Accepted',
            6 => 'IQA Rejected'
          ];
    }

    public function getEvidenceStatusAttribute($value)
    {
        if($value == 1)
            return 'Learner Submitted/Created';
        elseif($value == 2)
            return 'Assessor Accepted';
        elseif($value == 3)
            return 'Assessor Rejected';
        elseif($value == 4)
            return 'Learner Resubmitted';
    }

    public static function getEvidenceStatusDesc($value)
    {
        if($value == 1)
            return 'Learner Submitted/Created';
        elseif($value == 2)
            return 'Assessor Accepted';
        elseif($value == 3)
            return 'Assessor Rejected';
        elseif($value == 4)
            return 'Learner Resubmitted';
    }

    public function getEvidenceTypeAttribute($value)
    {
        if($value == self::TYPE_FILE)
            return 'File Upload';
        elseif($value == self::TYPE_URL)
            return 'External URL';
        elseif($value == self::TYPE_REFERENCE)
            return 'External Reference';
    }

    public function isFileUpload()
    {
        return $this->getOriginal('evidence_type') === self::TYPE_FILE;
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function getIcon($extraClasses = [])
    {
        $flatten = implode(' ', $extraClasses);
        switch($this->getOriginal('evidence_type'))
        {
            case self::TYPE_FILE:
                return '<i class="fa fa-file ' . $flatten . '" title="File upload evidence"></i>';
            case self::TYPE_URL:
                return '<i class="fa fa-external-link ' . $flatten . '" title="External url evidence"></i>';
            case self::TYPE_REFERENCE:
                return '<i class="fa fa-folder-o ' . $flatten . '" title="Reference to evidence"></i>';
            case self::TYPE_TYPED_SUBMISSION:
                return '<i class="fa fa-font ' . $flatten . '" title="Reference to evidence"></i>';
            default:
                return '<i class="fa fa-file-text-o ' . $flatten . '" title="File upload evidence"></i>';
        }
    }

    public function getMappings()
    {
        $mappings = DB::table('pc_evidence_mappings')
            ->join('portfolio_pcs', 'pc_evidence_mappings.portfolio_pc_id', '=', 'portfolio_pcs.id')
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->join('portfolios', 'portfolio_units.portfolio_id', '=', 'portfolios.id')
            ->select([
                DB::raw('portfolios.title AS portfolio_title'),
                'portfolios.qan',
                'portfolio_units.unit_owner_ref',
                'portfolio_units.unique_ref_number',
                DB::raw('portfolio_units.title AS unit_title'),
                'portfolio_pcs.reference',
                DB::raw('portfolio_pcs.title AS pc_title'),
                'portfolio_pcs.assessor_signoff'
            ])
            ->where('pc_evidence_mappings.tr_evidence_id', '=', $this->id) 
            ->where('portfolios.tr_id', '=', $this->training_record->id)
            ->orderBy('portfolios.id', 'asc')
            ->orderBy('portfolio_units.unit_sequence', 'asc')
            ->orderBy('portfolio_pcs.pc_sequence', 'asc')
            ->get();
        
        return $mappings;
    }

    public function isLearnerSubmitted()
    {
        return $this->getOriginal('evidence_status') === self::STATUS_LEARNER_SUBMITTED;
    }

    public function isAssessorAccepted()
    {
        return $this->getOriginal('evidence_status') === self::STATUS_ASSESSOR_ACCEPTED;
    }

    public function isIqaAccpeted()
    {
        return $this->getOriginal('iqa_status') === PortfolioUnitIqa::STATUS_IQA_ACCEPTED;
    }

    public function getTypedSubmissionContentAttribute()
    {
        $html = DB::table('tr_evidence_typed_submissions')
            ->where('tr_evidence_id', $this->id)
            ->value('evidence_text_content');

        return !is_null($html) ? preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html) : '';
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($evidence) {
            $evidence->mappings()->delete();
            $evidence->media()->each(function ($media) {
                $media->delete();
            });
        });
    }

    const STATUS_LEARNER_SUBMITTED = 1;
    const STATUS_ASSESSOR_ACCEPTED = 2;
    const STATUS_ASSESSOR_REJECTED = 3;
    const STATUS_LEARNER_RESUBMITTED = 4;
    const STATUS_IQA_ACCEPTED = 5;
    const STATUS_IQA_REJECTED = 6;

    const TYPE_FILE = 1;
    const TYPE_URL = 2;
    const TYPE_REFERENCE = 3;
    const TYPE_TYPED_SUBMISSION = 4;
} 
