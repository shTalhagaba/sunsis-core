<?php

namespace App\Models\Training;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TrainingStatusChangeLog extends Model 
{
    protected $table = 'tr_status_change_logs';

    protected $guarded = [];

    protected $dates = [
        'bil_last_day',
        'bil_expected_return',
        'restart_date',
        'revised_planned_end_date',
        'revised_epa_date',
        'achievement_date',
        'withdraw_date',
        'completion_date',
    ];

    public function training()
    {
    	return $this->belongsTo(TrainingRecord::class, 'tr_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
