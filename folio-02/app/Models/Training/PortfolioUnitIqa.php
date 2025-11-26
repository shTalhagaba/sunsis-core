<?php

namespace App\Models\Training;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PortfolioUnitIqa extends Model
{
    protected $table = 'portfolio_units_iqa';   

    protected $guarded = [];

    protected $dates = [
        'planned_completion_date',
        'reminder_date',
    ];

    public function unit()
    {
        return $this->belongsTo(PortfolioUnit::class, 'portfolio_unit_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_IQA_ACCEPTED => 'IQA Accepted',  
            self::STATUS_IQA_REFERRED => 'IQA Referred',  
        ];
    }

    public static function getDescription($status)
    {
        $statusList = self::getStatusList();
        return isset($statusList[$status]) ? $statusList[$status] : '';
    }

    const STATUS_IQA_ACCEPTED = 1;
    const STATUS_IQA_REFERRED = 2;
}
