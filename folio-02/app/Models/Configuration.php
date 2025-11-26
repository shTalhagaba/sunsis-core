<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $table = 'configuration';

    public $incrementing = false;

    public $timestamps = false;

    public $primaryKey = 'entity';

    protected $fillable = [
        'entity',
        'value',
    ];
}
