<?php

namespace App\Models\Lookups;

use App\Models\Programmes\Programme;

class ProgrammeTypeLookup extends BaseLookup
{    
    protected $guarded = [];

    protected $table = 'lookup_programme_types';

    public function programmes()
    {
        return $this->hasMany(Programme::class);
    }
}