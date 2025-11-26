<?php

namespace App\Models\OTLA;

use App\Models\Programmes\Programme;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class OTLA extends Model
{
    protected $table = 'otlas';

    protected $guarded = [];

    protected $casts = [
        'observation_start' => 'time',
        'observation_end' => 'time',
        'iqa_signed_date' => 'date',
        'observer_2_signed_date' => 'date',
        'coach_signed_date' => 'date',
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'ld_coach');
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class, 'programme_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function observer1()
    {
        return $this->belongsTo(User::class, 'observer_1');
    }

    public function observer2()
    {
        return $this->belongsTo(User::class, 'observer_2');
    }

    public function isIqaSigned()
    {
        return $this->iqa_signed === 1;
    }

    public function isObserver2Signed()
    {
        return $this->observer_2_signed === 1;
    }

    public function isCoachSigned()
    {
        return $this->coach_signed === 1;
    }

}
