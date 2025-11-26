<?php

namespace App\Models\Qualifications;

use Illuminate\Database\Eloquent\Model;

class QualificationUnit extends Model
{
    protected $table = 'qualification_units';

    protected $primaryKey = 'id';

    protected $fillable = [
        'qualification_id', 'unit_sequence', 'unit_group', 'unit_owner_ref', 'unique_ref_number', 'title',
        'glh', 'unit_credit_value', 'learning_outcomes', 'system_code',
    ];

    public function getForeignKey()
    {
        return 'unit_id';
    }

    public function qualification()
    {
        return $this->belongsTo(Qualification::class, 'qualification_id');
    }

    public function pcs()
    {
        return $this->hasMany(QualificationUnitPC::class, 'unit_id')->orderBy('pc_sequence');
    }

    public function getUnitGroupAttribute($value)
    {
        return $value == '' ? '' : \App\Models\LookupManager::getQualificationUnitGroups($value);
    }

    public static function getDDLUnitGroups($blank = true)
    {
        $ddl = \App\Models\LookupManager::getQualificationUnitGroups();
        return $blank ? ['' => ''] + $ddl : $ddl;
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($unit) { // before delete() method call this
            $unit->pcs()->each(function ($pc) {
                $pc->delete();
            });
        });
    }
}
