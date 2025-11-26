<?php

namespace App\Models\Programmes;

use App\Models\Qualifications\QualificationUnitPC;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class ProgrammeDeliveryPlanSessionTask extends Model implements HasMedia
{
    use HasMediaTrait;
    protected $table = 'programme_dp_tasks';

    protected $guarded = [];

    protected $fillable = [
        'dp_session_id',
        'title',
        'details',
        'status',
        'is_template',
        'updated_by',
        'created_by',
    ];

    public static function boot()
    {
        parent::boot();

        self::deleting(function ($task) {
            $task->media()->each(function ($media) {
                $media->delete();
            });
        });
    }

    public function session()
    {
        return $this->belongsTo(ProgrammeDeliveryPlanSession::class, 'dp_session_id');
    }

    public function pcs()
    {
        //qualification_unit_pcs
        return $this->hasManyThrough(ProgrammeQualificationUnitPC::class, ProgrammeDeliveryPlanSessionTaskPcs::class, 'task_id', 'id','id','pc_id');

    }

    public function pcIds()
    {
        return DB::table('programme_dp_task_pcs')->where('task_id', $this->id)->pluck('pc_id')->toArray();
    }
}
