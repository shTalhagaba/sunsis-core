<?php

namespace App\Models\Lookups;


class OtjTypeLookup extends BaseLookup
{    
    protected $guarded = [];

    protected $table = 'lookup_otj_types';

    const OTJ_TYPE_DP_SESSION = 18;
}