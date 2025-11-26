<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $table = 'licenses';

    protected $guarded = [];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function getStats()
    {
        $info['used'] = 0;
        $info['remaining'] = 0;
        if(License::count() > 0)
        {
            $info['used'] = \App\Models\Training\TrainingRecord::count();
            $info['remaining'] = License::latest('id')->first()->number_of_licenses - $info['used'];
        }

        return $info;
    }
}
