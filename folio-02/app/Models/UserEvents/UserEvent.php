<?php

namespace App\Models\UserEvents;

use App\Models\User;
use App\Traits\Filterable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserEvent extends Model
{
    use Filterable;

    protected $table = 'user_events';

    protected $guarded = [];

    

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'user_event_participants', 'event_id', 'user_id')
            ->orderBy('firstnames')
            ->withPivot(['status', 'comments']);
    }

    public function assignedIqa()
    {
        return $this->belongsTo(User::class, 'assign_iqa_id');
    }

    public function isPersonalToCreator()
    {
        return $this->personal;
    }

    public function canBeDeleted()
    {
        return ($this->isBooked() && !$this->isPast())
            || $this->isAssigned();
    }

    public function isBooked()
    {
        return $this->event_status == self::STATUS_BOOKED;
    }

    public function isAssigned()
    {
        return $this->task_status == self::STATUS_ASSIGNED;
    }

    public function isPast()
    {
        return Carbon::parse($this->end)->isPast();
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($event) {
            $event->participants()->detach();
        });
        static::updating(function ($task) {
            // If status is being set to COMPLETED and completed_at not set yet
            if ($task->isDirty('task_status') 
                && $task->task_status === self::STATUS_COMPLETED 
                && is_null($task->completed_at)) {
                $task->completed_at = now();
            }
        });
    }

    const STATUS_BOOKED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_CLOSED = 3;

    const STATUS_ASSIGNED = 1;
    const STATUS_IN_PROGRESS = 2;

    const STATUS_COMPLETED = 3;
    const STATUS_SIGNOFF = 4;


    const NOTIFICATION_INVITATION = 'invited_for_event';
    const NOTIFICATION_REMOVED = 'removed_from_event';
    const NOTIFICATION_ACCEPTED = 'event_accepted';
    const NOTIFICATION_DECLINED = 'event_declined';
    const NOTIFICATION_DELETED = 'event_deleted';
    const NOTIFICATION_CANCELLED = 'event_cancelled';
}