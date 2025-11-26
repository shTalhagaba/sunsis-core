<?php

namespace App\Http\Requests;

use App\Models\Lookups\TrainingStatusLookup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreTrainingRecordStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return 
            $this->user()->isActive() &&
            $this->user()->isStaff() &&
            $this->user()->can('update-training-record');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $training = request()->route('training');

        $fromStatus = $training->status_code;
        $toStatus = $this->input('status_code');

        $rules = [
            // 'status_code' => 'required|numeric|in:' . TrainingStatusLookup::where('id', '!=', $fromStatus)->pluck('id')->implode(',')
            'status_code' => 'required|numeric|in:' . TrainingStatusLookup::pluck('id')->implode(',')
        ];

        // conditional rules
        if($toStatus == TrainingStatusLookup::STATUS_BIL)
        {
            $rules['last_day_of_learning'] = 'required|date|after:' . $training->start_date->format('Y-m-d');
            $rules['expected_return_date'] = 'nullable|date|after:last_day_of_learning';
            $rules['existing_bil_reason_id'] = 'required|numeric';
        }
        elseif($toStatus == TrainingStatusLookup::STATUS_CONTINUING)
        {
            $latestStatusChange = $training->latestStatusChange();
            $rules['restart_date'] = ['required', 'date'];
            $rules['revised_planned_end_date'] = ['required', 'date', 'after:restart_date'];
            $rules['revised_epa_date'] = ['nullable', 'date', 'after:restart_date'];
            if($latestStatusChange)
            {
                if($fromStatus == TrainingStatusLookup::STATUS_BIL)
                {
                    $rules['restart_date'][] = 'after:' . $latestStatusChange->bil_last_day->format('Y-m-d');
                }
                elseif($fromStatus == TrainingStatusLookup::STATUS_COMPLETED)
                {
                    $rules['restart_date'][] = 'after:' . $training->actual_end_date->format('Y-m-d');
                }
            }            
        }
        elseif($toStatus == TrainingStatusLookup::STATUS_COMPLETED)
        {
            $rules['completion_date'] = 'required|date|after:' . $training->start_date->format('Y-m-d');
            $rules['achievement_date'] = 'nullable|date|after:' . $training->start_date->format('Y-m-d');
            $rules['learning_outcome_completion'] = 'nullable|numeric|in:' . DB::table('lookup_tr_learning_outcome')->pluck('id')->implode(',');
        }
        elseif($toStatus == TrainingStatusLookup::STATUS_WITHDRAWN)
        {
            $rules['withdraw_date'] = 'required|date|after_or_equal:' . $training->start_date->format('Y-m-d');
            $rules['withdrawal_reason'] = 'nullable|numeric|in:' . DB::table('lookup_tr_withdrawl_reasons')->pluck('id')->implode(',');
            $rules['learning_outcome_withdraw'] = 'nullable|numeric|in:' . DB::table('lookup_tr_learning_outcome')->pluck('id')->implode(',');
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'status_code' => 'training status',
            'existing_bil_reason_id' => 'break in learning reason',
            'learning_outcome_completion' => 'learning outcome',
            'learning_outcome_withdraw' => 'learning outcome',
        ];
    }
}
