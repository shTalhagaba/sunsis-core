<?php

namespace App\Models\Lookups\Central;

use App\Models\Lookups\BaseLookup;

class QualificationSsaLookup extends BaseLookup
{    
    protected $connection = 'mysql_folio_central';

    protected $table = 'lookup_qual_ssa';

    protected $primaryKey = 'id';

    protected $fillable = [];

}