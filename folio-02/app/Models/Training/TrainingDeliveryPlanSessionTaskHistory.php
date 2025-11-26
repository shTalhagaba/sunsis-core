<?php

namespace App\Models\Training;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class TrainingDeliveryPlanSessionTaskHistory extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $table = 'tr_tasks_history';

    protected $guarded = [];

    public function task()
    {
        return $this->belongsTo(TrainingDeliveryPlanSessionTask::class, 'tr_task_id');
    }

    public function training_record()
    {
    	return $this->belongsTo(TrainingRecord::class, 'tr_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function statusDescription()
    {
        switch ($this->status) 
        {
            case TrainingDeliveryPlanSessionTask::STATUS_PENDING:
                return 'PENDING';
            case TrainingDeliveryPlanSessionTask::STATUS_COMPLETED:
                return 'COMPLETED';
            case TrainingDeliveryPlanSessionTask::STATUS_REFERRED:
                return 'REFERRED';
            case TrainingDeliveryPlanSessionTask::STATUS_SUBMITTED:
                return 'SUBMITTED';
            default:
                return 'UNKNOWN';
        }
    }
} 
