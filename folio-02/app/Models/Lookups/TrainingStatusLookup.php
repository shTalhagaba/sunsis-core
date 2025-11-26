<?php

namespace App\Models\Lookups;

use App\Models\Training\TrainingRecord;
use ReflectionClass;

class TrainingStatusLookup extends BaseLookup
{
    const STATUS_CONTINUING = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_WITHDRAWN = 3;
    const STATUS_TEMP_WITHDRAWN = 4;
    const STATUS_DEACTIVATED = 5;
    const STATUS_ASSESSMENT_COMPLETE = 6;
    const STATUS_BIL = 7;
    
    protected $guarded = [];

    protected $table = 'lookup_tr_status';

    public function training_records()
    {
        return $this->hasMany(TrainingRecord::class, 'status_code');
    }

    static function getDescription($id)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        foreach($constants AS $key => $value)
        {
            if($value == $id)
            {
                return str_replace('STATUS_', '', $key);
            }
        }

        return parent::getDescription($id);
    }
}