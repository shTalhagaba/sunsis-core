<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    const LABEL_HOME = 'Home';
    const LABEL_WORK = 'Work';

    protected $fillable = [
        'label',
        'addressable_id',
        'addressable_type',
        'address_line_1',
        'address_line_2',
        'address_line_3',
        'address_line_4',
        'postcode',
        'region',
        'country',
        'telephone',
        'mobile',
        'fax',
        'latitude',
        'longitude'
    ];

    public function addressable()
    {
      return $this->morphTo();
    }
}
