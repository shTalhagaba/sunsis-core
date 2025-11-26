<?php

namespace App\Models\Lookups;

use App\Models\Programmes\ProgrammeQualification;
use App\Models\Qualifications\Qualification;
use App\Models\Training\Portfolio;

class QualificationTypeLookup extends BaseLookup
{    
    protected $guarded = [];

    protected $table = 'lookup_qual_types';

    public function qualifications()
    {
        return $this->hasMany(Qualification::class, 'type');
    }

    public function programmeQualifications()
    {
        return $this->hasMany(ProgrammeQualification::class, 'type');
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class, 'type');
    }

    const FS_QUAL_TYPES = [8, 12, 13];
}