<?php

namespace App\Models\Lookups;

use App\Models\Student;

class EthnicityLookup extends BaseLookup
{    
    protected $guarded = [];

    protected $table = 'lookup_ethnicities';

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}