<?php

namespace App\Models\Training;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class TrainingDeliveryPlanSessionTask extends Model implements HasMedia, Auditable
{
    use HasMediaTrait, \OwenIt\Auditing\Auditable;

    protected $table = 'tr_tasks';

    protected $fillable = [
        'tr_id',
        'dp_session_id',
        'pro_task_id',
        'title',
        'status',
        'complete_by',
        'start_date',
        'details',
        'created_by',
        'learner_signed_datetime',
        'assessor_signed_datetime',
        'verifier_signed_datetime',
    ];

    protected $dates = [
        'complete_by',
        'start_date',
        'learner_signed_datetime',
        'assessor_signed_datetime',
        'verifier_signed_datetime',
    ];

    public function session()
    {
        return $this->belongsTo(TrainingDeliveryPlanSession::class, 'dp_session_id');
    }

    public function trainingRecord()
    {
        return $this->belongsTo(TrainingRecord::class, 'tr_id');
    }

    public function history()
    {
        return $this->hasMany(TrainingDeliveryPlanSessionTaskHistory::class, 'tr_task_id');
    }

    public function pcs()
    {
        return DB::table('tr_task_pcs')->where('task_id', $this->id)->pluck('pc_id')->toArray();
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', self::STATUS_SUBMITTED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function hasLearnerSigned()
    {
        return $this->learner_signed_datetime;
    }

    public function hasAssessorSigned()
    {
        return $this->assessor_signed_datetime;
    }

    public function hasVerifierSigned()
    {
        return $this->verifier_signed_datetime;
    }

    public function statusDescription()
    {
        switch ($this->status) 
        {
            case self::STATUS_PENDING:
                return 'PENDING';
            case self::STATUS_COMPLETED:
                return 'COMPLETED';
            case self::STATUS_REFERRED:
                return 'REFERRED';
            case self::STATUS_SUBMITTED:
                return 'SUBMITTED';
            default:
                return 'UNKNOWN';
        }
    }

    public function isEditableByLearner()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_REFERRED]);
    }

    public function isReadyToAssess()
    {
        return $this->status != self::STATUS_COMPLETED;
    }

    public function isCompleted()
    {
        return $this->status == self::STATUS_COMPLETED;
    }

    public static function boot()
    {
        parent::boot();

        self::deleting(function ($task) {
            $task->media()->each(function ($media) {
                $media->delete();
            });
        });
    }

    const STATUS_PENDING = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_REFERRED = 3;
    const STATUS_SUBMITTED = 4;
}