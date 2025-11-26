<?php

namespace App\Models\Lookups\Central;

use App\Models\Lookups\BaseLookup;

class QualificationStatusLookup extends BaseLookup
{    
    protected $connection = 'mysql_folio_central';

    protected $table = 'lookup_qual_status';

    protected $primaryKey = 'id';

    protected $fillable = [];

}