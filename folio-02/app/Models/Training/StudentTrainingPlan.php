<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class StudentTrainingPlan extends Model
{
    protected $table = 'tr_training_plans';

    protected $guarded = [];

    public function training_record()
    {
    	return $this->belongsTo(TrainingRecord::class, 'tr_id');
    }

    public function getAwaitingPercentage() // orange bar
    {
        $orange = 0;
        $unit_ids = json_decode($this->plan_units);
        $units = \App\Models\Training\PortfolioUnit::with('portfolio')->whereIn('id', $unit_ids)->get();
        if(count($units) == 0)
            return 0;
        foreach($units AS $unit)
        {
            $orange += $unit->getAwaitingPercentage();
        }

        $result = round( ($orange/count($units)) );
        return $result > 100 ? 100 : $result ;

    }

    public function getProgressPercentageGreen() // green bar
    {
        $green = 0;
        $unit_ids = json_decode($this->plan_units);
        $units = \App\Models\Training\PortfolioUnit::with('portfolio')->whereIn('id', $unit_ids)->get();
        if(count($units) == 0)
            return 0;
        foreach($units AS $unit)
        {
            $green += $unit->getProgressPercentageGreen();
        }
        $result = round( ($green/count($units)) );
        return $result > 100 ? 100 : $result ;
    }

    public function getProgressPercentageBlue() // blue bar
    {
        $blue = 0;
        $unit_ids = json_decode($this->plan_units);
        $units = \App\Models\Training\PortfolioUnit::with('portfolio')->whereIn('id', $unit_ids)->get();
        if(count($units) == 0)
            return 0;
        foreach($units AS $unit)
        {
            $blue += $unit->getProgressPercentageBlue();
        }
        $result = round( ($blue/count($units)) );
        return $result > 100 ? 100 : $result ;
    }

}
