<?php

namespace App\Models\Training;

use App\Models\User;
use App\Traits\Filterable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TrainingDeliveryPlanSession extends Model implements HasMedia, Auditable
{
    use HasMediaTrait, \OwenIt\Auditing\Auditable, Filterable;

    protected $table = 'tr_dp_sessions';

    protected $guarded = [];

    protected $dates = [
        'student_sign_date',
        'assessor_sign_date',
        'actual_date',
        'revised_date',
        'session_start_date',
        'session_end_date',
    ];

    public function training()
    {
        return $this->belongsTo(TrainingRecord::class, 'tr_id');
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    public function ksb()
    {
        return $this->hasMany(TrainingDeliveryPlanSessionKSB::class, 'dp_session_id')->orderBy('sequence');
    }

    public function tasks()
    {
        return $this->hasMany(TrainingDeliveryPlanSessionTask::class, 'dp_session_id')->orderBy('created_at');
    }

    public function hasLearnerSigned()
    {
        return $this->student_sign;
    }

    public function hasAssessorSigned()
    {
        return $this->assessor_sign;
    }

    public function isLocked()
    {
        return $this->hasLearnerSigned() && $this->hasAssessorSigned();
    }

    public static function boot()
    {
        parent::boot();

        self::deleting(function ($session) {
            $session->ksb()->each(function ($ksb) {
                $ksb->delete();
            });
            $session->media()->each(function ($media) {
                $media->delete();
            });
        });
    }
}