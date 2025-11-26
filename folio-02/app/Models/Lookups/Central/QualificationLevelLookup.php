<?php

namespace App\Models\Lookups\Central;

use App\Models\Lookups\BaseLookup;

class QualificationLevelLookup extends BaseLookup
{    
    protected $connection = 'mysql_folio_central';

    protected $table = 'lookup_qual_levels';

    protected $primaryKey = 'id';

    protected $fillable = [];

}