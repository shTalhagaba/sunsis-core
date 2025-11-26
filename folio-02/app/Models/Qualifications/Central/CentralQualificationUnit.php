<?php

namespace App\Models\Qualifications\Central;

use Illuminate\Database\Eloquent\Model;


class CentralQualificationUnit extends Model
{
    protected $connection = 'mysql_folio_central';

    protected $table = 'qualification_units';

    protected $primaryKey = 'id';

    protected $fillable = [];

    public function qualification()
    {
        return $this->belongsTo(CentralQualification::class, 'qualification_id');
    }

    public function pcs()
    {
        return $this->hasMany(CentralQualificationUnitPC::class, 'unit_id')->orderBy('pc_sequence');
    }

    public function isMandatory()
    {
        return $this->getOriginal('unit_group') == 1;
    }
}
