<?php

namespace App\Models\Programmes;

use Illuminate\Database\Eloquent\Model;

class ProgrammeDeliveryPlanSession extends Model
{
    protected $table = 'programme_dp_sessions';

    protected $guarded = [];

    public function programme()
    {
    	return $this->belongsTo(Programme::class, 'programme_id');
    }
    public function tasks()
    {
    	return $this->hasMany(ProgrammeDeliveryPlanSessionTask::class, 'dp_session_id')->where('is_template', 0);
    }

    public function templateTasks()
    {
    	return $this->hasMany(ProgrammeDeliveryPlanSessionTask::class, 'dp_session_id')->where('is_template', 1);
    }
}
