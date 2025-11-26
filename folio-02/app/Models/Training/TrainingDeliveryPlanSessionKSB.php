<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class TrainingDeliveryPlanSessionKSB extends Model
{
    protected $table = 'tr_dp_session_ksb';

    protected $guarded = [];

    public function session()
    {
    	return $this->belongsTo(TrainingDeliveryPlanSession::class, 'dp_session_id');
    }

    public function pc()
    {
        return $this->belongsTo(PortfolioPC::class, 'tr_pc_id');
    }
}
