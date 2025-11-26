<?php

namespace App\Models\Lookups;

class EpaStatusLookup extends BaseLookup
{    
    public $incrementing = false;

    protected $guarded = [];

    protected $table = 'lookup_epa_status';

}