<?php

namespace App\Models\IQA;

use App\Models\Training\TrainingRecord;
use Illuminate\Database\Eloquent\Model;

class IqaSamplePlanTraining extends Model
{
    protected $table = 'iqa_sample_plan_trainings';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function plan()
    {
        return $this->belongsTo(IqaSamplePlan::class, 'iqa_sample_id');
    }

    public function record()
    {
        return $this->belongsTo(TrainingRecord::class, 'tr_id');
    }

}
