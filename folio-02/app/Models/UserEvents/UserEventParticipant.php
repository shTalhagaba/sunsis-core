<?php

namespace App\Models\UserEvents;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class UserEventParticipant extends Model
{
    protected $table = 'user_event_participants';

    protected $fillable = [
        'status',
        'comments',
    ];

    public function event()
    {
        return $this->belongsTo(UserEvent::class, 'event_id');
    }

    public function participant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isAccepted()
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isDeclined()
    {
        return $this->status === self::STATUS_DECLINED;
    }

    static function renderStatus($id)
    {
        return $id == self::STATUS_INVITED ? '<span class="text-primary">INVITED</span>' : 
            (
                $id == self::STATUS_ACCEPTED ? '<span class="text-success">ACCEPTED</span>' :
                (
                    $id == self::STATUS_DECLINED ? '<span class="text-danger">DECLINED</span>' :
                    '' 
                ) 
            );
    }

    static function getStatusDescription($id)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        foreach($constants AS $key => $value)
        {
            if($value == $id)
            {
                return str_replace('STATUS_', '', $key);
            }
        }

        return '';
    }

    const STATUS_INVITED = 1;
    const STATUS_ACCEPTED = 2;
    const STATUS_DECLINED = 3;
}
