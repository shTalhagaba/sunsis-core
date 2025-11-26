<?php

namespace App\Models\IQA;

use Illuminate\Database\Eloquent\Model;

class IqaSamplePlanQualification extends Model
{
    protected $table = 'iqa_sample_plan_qualifications';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function plan()
    {
        return $this->belongsTo(IqaSamplePlan::class, 'iqa_sample_id');
    }

    public function units()
    {
        return $this->hasMany(IqaSamplePlanUnit::class, 'qualification_id');
    }
}
