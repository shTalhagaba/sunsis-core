<?php

namespace App\Models\Programmes;

use App\Models\Lookups\QualificationTypeLookup;
use Illuminate\Database\Eloquent\Model;

class ProgrammeQualification extends Model
{
    protected $table = 'programme_qualifications';

    protected $fillable = [
        'programme_id',
        'main',
        'sequence',
        'proportion',
        'duration',
        'offset',
        'qan',
        'title',
        'min_glh',
        'max_glh',
        'glh',
        'total_credits',
        'assessment_methods',
        'tbl_qualification_id',
        'type',
    ];

    public function programme()
    {
    	return $this->belongsTo(Programme::class, 'programme_id');
    }

    public function units()
    {
    	return $this->hasMany(ProgrammeQualificationUnit::class, 'programme_qualification_id')
            ->orderBy('unit_sequence')
            ->orderBy('programme_qualification_units.id');
    }

    public function mandatoryUnitsCount()
    {
        return $this->units()->where('unit_group', 1)->count();
    }

    public function optionalUnitsCount()
    {
        return $this->units()->where('unit_group', 2)->count();
    }

    public function isFsQualification()
    {
        return in_array($this->type, QualificationTypeLookup::FS_QUAL_TYPES);
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($qualification) {
            $qualification->units()->each(function ($unit) {
                $unit->delete();
            });
        });
    }
}
