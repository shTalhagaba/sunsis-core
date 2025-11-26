<?php

namespace App\Models\Qualifications\Central;

use App\Models\Lookups\Central\QualificationOwnerLookup;
use App\Models\Lookups\Central\QualificationSsaLookup;
use App\Models\Lookups\Central\QualificationStatusLookup;
use App\Models\Lookups\Central\QualificationTypeLookup;
use App\Models\Lookups\Central\QualificationUnitGroupLookup;
use Illuminate\Database\Eloquent\Model;


class CentralQualification extends Model
{
    protected $connection = 'mysql_folio_central';

    protected $table = 'qualifications';

    protected $primaryKey = 'id';

    protected $fillable = [];

    public function units()
	{
		return $this->hasMany(CentralQualificationUnit::class, 'qualification_id')->orderBy('unit_sequence');
    }

    public function awardingOrg()
    {
        return $this->hasOne(QualificationOwnerLookup::class, 'owner_org_rn', 'owner_org_rn');
    }

    public function qualType()
    {
        return $this->hasOne(QualificationTypeLookup::class, 'id', 'type');
    }

    public function qualSsa()
    {
        return $this->hasOne(QualificationSsaLookup::class, 'id', 'ssa');
    }

    public function qualStatus()
    {
        return $this->hasOne(QualificationStatusLookup::class, 'id', 'status');
    }

    public function mandatoryUnitsCount()
    {
        return $this->units()->where('unit_group', QualificationUnitGroupLookup::GROUP_MANDATORY)->count();
    }

    public function optionalUnitsCount()
    {
        return $this->units()->where('unit_group', QualificationUnitGroupLookup::GROUP_OPTIONAL)->count();
    }
}
