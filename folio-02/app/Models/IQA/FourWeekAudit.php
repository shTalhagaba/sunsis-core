<?php

namespace App\Models\IQA;

use App\Models\Training\TrainingRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;

class FourWeekAudit extends Model
{
    use Filterable;

    protected $table = 'tr_iqa_four_week_audits';

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $dates = [
        'date_of_portfolio_audit',
        'completed_by_date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function training()
    {
        return $this->belongsTo(TrainingRecord::class, 'tr_id');
    }

    public function signed()
    {
        return $this->iqa_signed === 1;
    }

    public function completedBy()
    {
        if (!$this->completed_by_id) 
        {
            return 'Not completed';
        }

        $user = User::find($this->completed_by_id);
        return $user ? $user->full_name : 'Unknown';
    }
}
