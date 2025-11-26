<?php

namespace App\Models\Lookups;

use ReflectionClass;
use Illuminate\Database\Eloquent\Model;

class PortfolioStatusLookup extends Model
{
    const STATUS_CONTINUING = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_WITHDRAWN = 3;
    const STATUS_TEMP_WITHDRAWN = 4;
    const STATUS_DEACTIVATED = 5;
    const STATUS_ASSESSMENT_COMPLETE = 6;
    const STATUS_BIL = 7;
    const STATUS_EXEMPT = 8;
    
    protected $guarded = [];

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

        return null;
    }

    static function getSelectData()
    {
        return [
            self::STATUS_BIL => 'Break in Learning',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CONTINUING => 'Continuing',
            self::STATUS_DEACTIVATED => 'Deactivated',
            self::STATUS_EXEMPT => 'Exempt',
            self::STATUS_WITHDRAWN => 'Withdrawn',
        ];
    }
}