<?php

namespace App\Models\Organisations;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'org_locations';

    protected $fillable = [
        'organisation_id',
        'is_legal_address',
        'title',
        'address_line_1',
        'address_line_2',
        'address_line_3',
        'address_line_4',
        'postcode',
        'telephone',
        'mobile',
        'fax',
        'email',
        'sunesis_id'
    ];

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function contacts()
    {
        return $this->hasMany(OrganisationContact::class);
    }

    public function students()
    {
        return $this->hasMany(User::class, 'employer_location', 'id'); #foreign_key, local_key
    }
}
