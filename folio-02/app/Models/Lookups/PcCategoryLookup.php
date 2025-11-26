<?php

namespace App\Models\Lookups;

use ReflectionClass;

class PcCategoryLookup extends BaseLookup
{
    const KSB_KNOWLEDGE = 9;
    const KSB_SKILLS = 10;
    const KSB_BEHAVIOURS = 11;
    
    protected $guarded = [];

    protected $table = 'lookup_evidence_categories';

    static function getDescription($id)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        foreach($constants AS $key => $value)
        {
            if($value == $id)
            {
                return str_replace('KSB_', '', $key);
            }
        }

        return parent::getDescription($id);
    }
}