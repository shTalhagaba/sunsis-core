<?php

namespace App\Models\Lookups;

use App\Models\User;
use ReflectionClass;

class UserTypeLookup extends BaseLookup
{
    const TYPE_ADMIN = 1;
    const TYPE_TUTOR = 2;
    const TYPE_ASSESSOR = 3;
    const TYPE_VERIFIER = 4;
    const TYPE_STUDENT = 5;
    const TYPE_MANAGER = 8;
    const TYPE_SYSTEM_VIEWER = 12;
    const TYPE_EQA = 17;
    const TYPE_EMPLOYER_USER = 18;
    const TYPE_QUALITY_MANAGER = 19;

    protected $guarded = [];

    protected $table = 'lookup_user_types';

    public function users()
    {
        return $this->hasMany(User::class);
    }

    static function getDescription($id)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        foreach ($constants as $key => $value) {
            if ($value == $id) {
                return str_replace('TYPE_', '', $key);
            }
        }

        return parent::getDescription($id);
    }
}
