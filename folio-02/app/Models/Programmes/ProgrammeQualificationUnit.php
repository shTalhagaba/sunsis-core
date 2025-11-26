<?php

namespace App\Models\Programmes;

use Illuminate\Database\Eloquent\Model;

class ProgrammeQualificationUnit extends Model
{
    protected $table = 'programme_qualification_units';

    protected $fillable = [
        'programme_qualification_id',
        'unit_sequence',
        'unit_group',
        'unit_owner_ref',
        'unique_ref_number',
        'title',
        'glh',
        'unit_credit_value',
        'learning_outcomes',
        'system_code',
        'unit_status',
    ];

    public function qualification()
    {
    	return $this->belongsTo(ProgrammeQualification::class, 'programme_qualification_id');
    }

    public function pcs()
    {
    	return $this->hasMany(ProgrammeQualificationUnitPC::class, 'programme_qualification_unit_id')->orderBy('pc_sequence');
    }

    public function getUnitGroupAttribute($value)
    {
        return $value == '' ? '' : \App\Models\LookupManager::getQualificationUnitGroups($value);
    }

    public function isMandatory()
    {
        return $this->getOriginal('unit_group') == 1;
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($unit) {
            $unit->pcs()->each(function ($pc) {
                $pc->delete();
            });
        });
    }
}
