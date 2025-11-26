<?php

namespace App\Models\Lookups\Central;

use App\Models\Lookups\BaseLookup;

class QualificationPcCategoryLookup extends BaseLookup
{    
    protected $connection = 'mysql_folio_central';

    protected $table = 'lookup_evidence_categories';

    protected $primaryKey = 'id';

    protected $fillable = [];

}