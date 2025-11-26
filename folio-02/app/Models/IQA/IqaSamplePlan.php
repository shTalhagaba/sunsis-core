<?php

namespace App\Models\IQA;

use App\Models\Programmes\Programme;
use App\Models\Training\PortfolioUnitIqa;
use App\Models\Training\TrainingRecord;
use App\Models\User;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class IqaSamplePlan extends Model implements ContractsAuditable
{
    use Filterable, Auditable;

    protected $table = 'iqa_sample_plans';

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $dates = [
        'completed_by_date',
    ];

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class, 'programme_id');
    }

    public function units()
    {
        return $this->hasMany(IqaSamplePlanUnit::class, 'iqa_sample_id')->orderBy('qual_title')->orderBy('title');
    }

    public function trainings()
    {
        return $this->hasMany(IqaSamplePlanTraining::class, 'iqa_sample_id');
    }
    
    public function qualifications()
    {
        return $this->hasMany(IqaSamplePlanQualification::class, 'iqa_sample_id');
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function entries()
    {
        return $this->hasMany(IqaPlanEntry::class, 'iqa_plan_id');
    }

    public static function getTypeList()
    {
        return [
            'formative' => 'Formative',
            'summative' => 'Summative',
            'observation' => 'Observation',
            'interview' => 'Interview',
        ];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_SCHEDULED => ucwords(self::STATUS_SCHEDULED),
            self::STATUS_ONGOING => ucwords(self::STATUS_ONGOING),
            self::STATUS_COMPLETED => ucwords(self::STATUS_COMPLETED),
        ];
    }

    public function updateStatus()
    {
        $trainingIds = $this->trainings()->select('tr_id')->pluck('tr_id')->toArray();
        $unitRefs = $this->units()->select('system_code')->pluck('system_code')->toArray();

        $portfolioUnits = DB::table('portfolio_units')
            ->join('portfolios', 'portfolio_units.portfolio_id', '=', 'portfolios.id')
            ->whereIn('portfolios.tr_id', $trainingIds)
            ->whereIn('portfolio_units.system_code', $unitRefs)
            ->get();

        $acceptedUnits = 0;
        $referredUnits = 0;
        $iqaCompleted = 0;
        foreach($portfolioUnits AS $unit)
        {
            if($unit->iqa_status == PortfolioUnitIqa::STATUS_IQA_ACCEPTED)
            {
                $acceptedUnits++;
            }
            if($unit->iqa_status == PortfolioUnitIqa::STATUS_IQA_REFERRED)
            {
                $referredUnits++;
            }
            if($unit->iqa_completed)
            {
                $iqaCompleted++;
            }
        }    

        $status = ($acceptedUnits > 0 || $referredUnits > 0) ? self::STATUS_ONGOING : self::STATUS_SCHEDULED;
        if( count($unitRefs) == $acceptedUnits && count($unitRefs) == $iqaCompleted )
        {
             $status = self::STATUS_COMPLETED;
        }

        $this->update(['status' => $status]);
    }

    public function getStatusLabel()
    {
        return $this->status == self::STATUS_COMPLETED ? 
            '<span class="text-success">' . ucwords(self::STATUS_COMPLETED) . '</span>' :
            (
                $this->status == self::STATUS_ONGOING ? 
                    '<span class="text-warning">' . ucwords(self::STATUS_ONGOING) . '</span>' :
                    '<span class="text-primary">' . ucwords(self::STATUS_SCHEDULED) . '</span>'
            );
    }

    public function isScheduled()
    {
        return $this->status === self::STATUS_SCHEDULED;
    }

    public function isOngoing()
    {
        return $this->status === self::STATUS_ONGOING;
    }
    
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';
}
