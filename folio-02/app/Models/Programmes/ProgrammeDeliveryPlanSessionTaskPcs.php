<?php

namespace App\Models\Programmes;

use App\Models\Qualifications\QualificationUnitPC;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProgrammeDeliveryPlanSessionTaskPcs extends Model
{
    protected $table = 'programme_dp_task_pcs';

    protected $guarded = [];

}
