<?php

namespace App\Models\Qualifications;

use App\Models\Lookups\QualificationTypeLookup;
use App\Models\Tags\Tag;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Qualification extends Model
{
    use Filterable;

    protected $primaryKey = 'id';

    protected $fillable = [
        'qan', 'title', 'owner_org_rn', 'level', 'sub_level', 'eqf_level', 'type', 'total_credits', 'ssa', 'status', 
        'regulation_start_date', 'operational_start_date', 'operational_end_date', 'certification_end_date', 'min_glh', 
        'max_glh', 'total_qual_time', 'glh', 'offered_in_england', 'offerend_in_ni', 'overall_grading_type', 'assessment_methods',
        'ni_discount_code', 'gce_size_equivalence', 'gcse_size_equivalence', 'entitlement_framework_designation', 'grading_scale',
        'specialism', 'pathways', 'approved_for_DEL_funded_programme', 'link_to_specs', 'system_code',
    ];

    public function units()
	{
		return $this->hasMany(QualificationUnit::class, 'qualification_id')
            ->orderBy('unit_sequence')
            ->orderBy('qualification_units.id');
    }

    public function mandatoryUnitsCount()
    {
        return $this->units()->where('unit_group', 1)->count();
    }

    public function optionalUnitsCount()
    {
        return $this->units()->where('unit_group', 2)->count();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function tags() 
    {
        return $this->morphToMany(Tag::class, 'taggable')
            ->where('type', 'Qualification')
            ->orderBy('order_column');
    }

	public function getRegulationStartDateAttribute($value)
    {
        return !is_null($value) ? Carbon::parse($value)->format('d/m/Y') : '';
    }

    public function getsSAAttribute($value)
    {
    	return $value == '' ? : \App\Models\LookupManager::getQualificationSSAs($value);
    }

    public function getTypeAttribute($value)
    {
        return $value == '' ? : \App\Models\LookupManager::getQualificationTypes($value);
    }

    public function getStatusAttribute($value)
    {
        return $value == '' ? : \App\Models\LookupManager::getQualificationStatus($value);
    }

    public function getOwnerOrgNameAttribute()
    {
        return $this->owner_org_rn == '' ? : \App\Models\LookupManager::getQualificationOwnersName($this->owner_org_rn);
    }

    public function getLevelAttribute($value)
    {
        return $value == '' ? '' : \App\Models\LookupManager::getQualificationLevels($value);
    }

    public function getOwnerOrgAcronymAttribute()
    {
        return $this->owner_org_rn == '' ? : \App\Models\LookupManager::getQualificationOwnersAcronym($this->owner_org_rn);
    }

    public function getOperationalStartDateAttribute($value)
    {
        return $value == '' ? '' : Carbon::parse($value)->format('d/m/Y');
    }

    public function getOperationalEndDateAttribute($value)
    {
        return $value == '' ? '' : Carbon::parse($value)->format('d/m/Y');
    }

    public function getCertificationEndDateAttribute($value)
    {
        return $value == '' ? '' : Carbon::parse($value)->format('d/m/Y');
    }
    // attributes conversion - end

    public static function getQualificationLevels($blank = true)
    {
         $levels = \App\Models\LookupManager::getQualificationLevels();
         return $blank ? ['' => ''] + $levels : $levels;
    }

    public static function getQualificationOwners($blank = true)
    {
         $owners = \App\Models\LookupManager::getQualificationOwnersName();
         return $blank ? ['' => ''] + $owners : $owners;
    }

    public static function getQualificationTypes($blank = true)
    {
        $types = \App\Models\LookupManager::getQualificationTypes();
        return $blank ? ['' => ''] + $types : $types;
    }

    public static function getQualificationSSA($blank = true)
    {
        $ssa = \App\Models\LookupManager::getQualificationSSAs();
        return $blank ? ['' => ''] + $ssa : $ssa;
    }

    public static function getQualificationStatus($blank = true)
    {
        $status = \App\Models\LookupManager::getQualificationStatus();
        return $blank ? ['' => ''] + $status : $status;
    }

    public static function getQualificationOverallGradingTypes($blank = true)
    {
        $types = ['Pass/Fail' => 'Pass/Fail', 'Graded' => 'Graded'];
        return $blank ? ['' => ''] + $types : $types;
    }

    public static function getOwnerNameDescription($id)
    {
        if($id == '')
            return;

        return \App\Models\LookupManager::getQualificationOwnersName($id);
    }

    public function isFsQualification()
    {
        return in_array($this->type, QualificationTypeLookup::FS_QUAL_TYPES);
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($qualification) {
            $qualification->units()->each(function ($unit) {
                $unit->delete();
            });
        });
    }
}
