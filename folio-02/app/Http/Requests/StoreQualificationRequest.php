<?php

namespace App\Http\Requests;

use App\Models\LookupManager;
use Illuminate\Foundation\Http\FormRequest;

class StoreQualificationRequest extends FormRequest
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
            ($this->user()->can('create-qualification') || $this->user()->can('update-qualification'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'qan' => 'required|min:4|max:8', //|unique:qualifications,qan,' . $request->id,
            'title' => 'required|min:10|max:250',
            'total_credits' => 'nullable|numeric',
            'regulation_start_date' => 'nullable|date',
            'operational_start_date' => 'nullable|date',
            'operational_end_date' => 'nullable|date',
            'certification_end_date' => 'nullable|date',
            'min_glh' => 'nullable|numeric|lte:max_glh',
            'max_glh' => 'nullable|numeric|gte:min_glh',
            'glh' => 'required|numeric|gte:min_glh|lte:max_glh',
            'total_qual_time' => 'nullable|numeric',
            'overall_grading_type' => 'nullable|string|max:15',
            'assessment_methods' => 'nullable|string|max:255',
            'link_to_specs' => 'nullable|string',
            'status' => 'required|numeric|in:' . implode(',', array_keys(LookupManager::getQualificationStatus())),
            'owner_org_rn' => 'required|max:8',
            'level' => 'required|in:' . implode(',', array_keys(LookupManager::getQualificationLevels())),
            'type' => 'required|numeric',
            'ssa' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'qan.min' => 'Qualification number must be at least 4 characters long.',
        ];
    }

    public function attributes()
    {
        return [
            'qan' => 'Qualification number',
            'owner_org_rn' => 'Owner',
            'ssa' => 'SSA (Sector Subject Area)',
        ];
    }
}
