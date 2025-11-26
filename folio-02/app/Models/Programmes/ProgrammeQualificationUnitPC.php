<?php

namespace App\Models\Programmes;

use Illuminate\Database\Eloquent\Model;

class ProgrammeQualificationUnitPC extends Model
{
    protected $table = 'programme_qualification_unit_pcs';

    protected $fillable = [
        'programme_qualification_unit_id',
        'pc_sequence',
        'reference',
        'category',
        'title',
        'min_req_evidences',
        'delivery_hours',
        'description',
        'system_code',
    ];

    public function unit()
    {
    	return $this->belongsTo(ProgrammeQualificationUnit::class, 'programme_qualification_unit_id');
    }

    public function getCategoryAttribute($value)
    {
        return $value == '' ? '' : \App\Models\LookupManager::getQualificationUnitPcCategory($value);
    }
}
