<?php

namespace App\Models\Lookups;

use App\Models\Training\Portfolio;
use ReflectionClass;

class TrainingOutcomeLookup extends BaseLookup
{
    const OUTCOME_ACHIEVED = 1;
    const OUTCOME_PARTIAL_ACHIEVEMENT = 2;
    const OUTCOME_NO_ACHIEVEMENT = 3;
    const OUTCOME_COMPLETED_AND_UNKNOWN = 4;
    
    protected $guarded = [];

    protected $table = 'lookup_tr_learning_outcome';

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class, 'learning_outcome');
    }

    static function getDescription($id)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        foreach($constants AS $key => $value)
        {
            if($value == $id)
            {
                return str_replace('OUTCOME_', '', $key);
            }
        }

        return parent::getDescription($id);
    }
}