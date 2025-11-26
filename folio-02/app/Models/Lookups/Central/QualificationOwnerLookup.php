<?php

namespace App\Models\Lookups\Central;

use App\Models\Lookups\BaseLookup;

class QualificationOwnerLookup extends BaseLookup
{    
    protected $connection = 'mysql_folio_central';

    protected $table = 'lookup_qual_owners';

    protected $primaryKey = 'owner_org_rn';

    protected $fillable = [];

}