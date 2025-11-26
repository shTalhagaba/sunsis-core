<?php

namespace App\Http\Requests;

use App\Models\IQA\IqaSamplePlan;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Programmes\Programme;
use App\Models\Qualifications\Qualification;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreIqaSamplePlanRequest extends FormRequest
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
            ($this->user()->can('create-iqa-sample-plan') || $this->user()->can('update-iqa-sample-plan'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'nullable|max:70',
            'type' => 'nullable|string|in:'.implode(',', array_keys(IqaSamplePlan::getTypeList())),
            'completed_by_date' => 'nullable|date',
            'programme_id' => 'nullable|numeric|in:'.Programme::pluck('id')->implode(','),
            'qualifications' => 'nullable|array|min:1',
            'verifier_id' => 'required|numeric|in:'.User::withActiveAccess()->where('user_type', UserTypeLookup::TYPE_VERIFIER)->pluck('id')->implode(','),
            'assessor_id' => 'required|numeric|in:'.User::withActiveAccess()->where('user_type', UserTypeLookup::TYPE_ASSESSOR)->pluck('id')->implode(','),
            'learning_aim_id' => 'required|in:'.Qualification::distinct()->pluck('id')->implode(','),
        ];
    }

    public function attributes()
    {
        return [
            'programme_id' => 'programme',
        ];
    }
}
