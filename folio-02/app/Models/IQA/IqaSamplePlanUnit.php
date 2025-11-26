<?php

namespace App\Models\IQA;

use Illuminate\Database\Eloquent\Model;

class IqaSamplePlanUnit extends Model
{
    protected $table = 'iqa_sample_plan_units';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function plan()
    {
        return $this->belongsTo(IqaSamplePlan::class, 'iqa_sample_id');
    }

    public function qualification()
    {
        return $this->belongsTo(IqaSamplePlanQualification::class, 'qualification_id');
    }
}
