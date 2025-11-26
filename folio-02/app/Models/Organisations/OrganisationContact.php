<?php

namespace App\Models\Organisations;

use Illuminate\Database\Eloquent\Model;

class OrganisationContact extends Model
{
    protected $table = 'organisation_contacts';

    protected $guarded = [];

    public function organisation()
    {
    	return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function location()
    {
    	return $this->belongsTo(Location::class, 'location_id');
    }

}
