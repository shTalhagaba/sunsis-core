<?php

namespace App\Models\IQA;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class IqaPlanEntry extends Model
{
    protected $table = 'iqa_plan_entries';

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $dates = [
        'planned_completion_date',
        'reminder_date',
        'completion_date',
    ];

    public function plan()
    {
        return $this->belongsTo(IqaSamplePlan::class, 'iqa_plan_id');
    }

    public function completed()
    {
        return $this->iqa_status === self::IQA_ENTRY_STATUS_COMPLETED;
    }

    public static function getAssessmentMethodDesc($id, $abbr = true)
    {
        if (!Session::exists('lookups.lookup_tr_evidence_categories')) {
            $list = DB::table('lookup_tr_evidence_categories')->get();
            Session::put('lookups.lookup_tr_evidence_categories', $list);
        } else {
            $list = Session::get('lookups.lookup_tr_evidence_categories');
        }

        //return $abbr ? $list->where('id', $id)->first()->abbr : $list->where('id', $id)->first()->description;

        $record = $list->where('id', $id)->first();

        if (! $record) {
            return 'N/A';
        }

        // Your table only has description, so always return it
        return $record->description;
    }

    const IQA_ENTRY_STATUS_PLANNED = 'PLANNED';
    const IQA_ENTRY_STATUS_IN_PROGRESS = 'IN_PROGRESS';
    const IQA_ENTRY_STATUS_COMPLETED = 'COMPLETED';
}
