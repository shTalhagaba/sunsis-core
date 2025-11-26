<?php

namespace App\Models\Qualifications;

use App\Models\Training\TrainingRecordEvidence;
use Illuminate\Database\Eloquent\Model;

class QualificationUnitPC extends Model
{
    protected $table = 'qualification_unit_pcs';

	protected $fillable = [
        'unit_id', 'pc_sequence', 'reference', 'category', 'title', 'min_req_evidences', 'delivery_hours', 'description', 'system_code',
    ];

    public function unit()
    {
    	return $this->belongsTo(QualificationUnit::class, 'unit_id');
    }

    public function mapped_evidences()
    {
        return $this->belongsToMany(TrainingRecordEvidence::class, 'pc_evidence_mappings', 'portfolio_pc_id', 'tr_evidence_id')
            ->withPivot('status', 'created_by');
    }

    public static function getDDLEvidenceTypes($blank = true)
    {
        $type = \App\Models\LookupManager::getEvidenceTypes();
        return $blank ? ['' => ''] + $type : $type;
    }

    public static function getDDLEvidenceCategories($blank = true)
    {
        $category = \App\Models\LookupManager::getQualificationUnitPcCategory();
        return $blank ? ['' => ''] + $category : $category;
    }

    public static function getDDLEvidenceAssessmentMethods($blank = true)
    {
        $methods = \App\Models\LookupManager::getEvidenceAssessmentMethod();
        return $blank ? ['' => ''] + $methods : $methods;
    }

    public function getAssessmentMethodAttribute($value)
    {
        return $value == '' ? '' : \App\Models\LookupManager::getEvidenceAssessmentMethod($value);
    }

    public function getCategoryAttribute($value)
    {
        return $value == '' ? '' : \App\Models\LookupManager::getQualificationUnitPcCategory($value);
    }

    public function getEvidenceTypeAttribute($value)
    {
        return $value == '' ? '' : \App\Models\LookupManager::getEvidenceTypes($value);
    }
}
