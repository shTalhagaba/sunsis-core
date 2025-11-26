<?php

namespace App\Models\Qualifications\Central;

use Illuminate\Database\Eloquent\Model;


class CentralQualificationUnitPC extends Model
{
    protected $connection = 'mysql_folio_central';

    protected $table = 'qualification_unit_pcs';

    protected $primaryKey = 'id';

    protected $fillable = [];

    public function unit()
    {
    	return $this->belongsTo(CentralQualificationUnit::class, 'unit_id');
    }
}
