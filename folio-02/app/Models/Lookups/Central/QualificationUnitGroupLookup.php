<?php

namespace App\Models\Lookups\Central;

use App\Models\Lookups\BaseLookup;

class QualificationUnitGroupLookup extends BaseLookup
{    
    protected $connection = 'mysql_folio_central';

    protected $table = 'lookup_unit_groups';

    protected $primaryKey = 'id';

    protected $fillable = [];

    const GROUP_MANDATORY = 1;
    const GROUP_OPTIONAL = 2;
}