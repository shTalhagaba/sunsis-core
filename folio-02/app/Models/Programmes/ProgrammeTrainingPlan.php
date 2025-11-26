<?php

namespace App\Models\Programmes;

use Illuminate\Database\Eloquent\Model;

class ProgrammeTrainingPlan extends Model
{
    protected $table = 'programme_training_plans';

    protected $guarded = [];

    public function programme()
    {
    	return $this->belongsTo(App\Models\Programmes\Programme::class, 'programme_id');
    }
}
