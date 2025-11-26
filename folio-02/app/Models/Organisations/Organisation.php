<?php

namespace App\Models\Organisations;

use App\Models\MediaSection;
use App\Models\Student;
use App\Models\Tags\Tag;
use App\Models\User;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;


class Organisation extends Model implements HasMedia
{
    use Filterable, HasMediaTrait;

    protected $table = 'orgs';

    protected $fillable = [
        'org_type',
        'legal_name',
        'trading_name',
        'company_number',
        'vat_number',
        'sector',
        'edrs',
        'active',
        'onefile_id',
        'sunesis_id',
    ];

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function contacts()
    {
        return $this->hasMany(OrganisationContact::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')
            ->where('type', 'Organisation')
            ->orderBy('order_column');
    }

    public function mediaSections()
    {
        return $this->morphToMany(
            MediaSection::class,
            'model',
            'media_section_has_models',
            'model_id',
            'section_id'
        )
            ->where('media_sections.type', 'Organisation')
            ->orderBy('media_sections.name');
    }

    public function students()
    {
        return $this->hasManyThrough(
            Student::class,
            'App\Models\Organisations\Location',
            'organisation_id', // Foreign key on locations table...
            'employer_location', // Foreign key on users table...
            'id', // Local key on orgs table...
            'id' // Local key on locations table...
        );
    }

    public function scopeActive($query, $active = 1)
    {
        return $query->where('active', $active);
    }

    public function scopeEmployers($query)
    {
        return $query->where('org_type', self::TYPE_EMPLOYER);
    }

    public function scopeSystemOwner($query)
    {
        return $query->where('org_type', self::TYPE_SYSTEM_OWNER);
    }

    public function isEmployer()
    {
        return $this->org_type === self::TYPE_EMPLOYER;
    }

    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            'App\Models\Organisations\Location',
            'organisation_id', // Foreign key on locations table...
            'employer_location', // Foreign key on users table...
            'id', // Local key on orgs table...
            'id' // Local key on locations table...
        );
    }

    public static function getDDLOrgSectors($blank = true)
    {
        $list = \App\Models\LookupManager::getOrganisationSectors();
        return $blank ? ['' => ''] + $list : $list;
    }

    public function getRouteName()
    {
        if ($this->org_type == self::TYPE_EMPLOYER) {
            return 'employers.show';
        }
    }

    public function getSectorAttribute($value)
    {
        return $value == '' ? '' : \App\Models\LookupManager::getOrganisationSectors($value);
    }

    public function mainLocation()
    {
        return $this->locations()->where('is_legal_address', 1)->first();
    }

    public function typeDescription()
    {
        switch ($this->org_type) {
            case self::TYPE_SYSTEM_OWNER:
                return 'System Owner';
            case self::TYPE_EMPLOYER:
                return 'Employer';
            case self::TYPE_TRAINING_PROVIDER:
                return 'Training Provider';
            default:
                return '';
        }
    }

    const TYPE_SYSTEM_OWNER = 1;
    const TYPE_EMPLOYER = 2;
    const TYPE_TRAINING_PROVIDER = 3;
}
