<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $table = 'calendar_events';

    protected $guarded = [];

    public function user()
    {
    	return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
