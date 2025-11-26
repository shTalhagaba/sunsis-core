<?php

namespace App\Http\Requests;

use App\Models\Lookups\ProgrammeTypeLookup;
use Illuminate\Foundation\Http\FormRequest;

class StoreProgrammeRequest extends FormRequest
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
            ($this->user()->can('create-programme') || $this->user()->can('update-programme'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:100',
            'duration' => 'required|numeric|digits_between:1,2',
            'epa_duration' => 'required|numeric|digits_between:1,2',
            'programme_type' => 'nullable|numeric|in:'.ProgrammeTypeLookup::pluck('id')->implode(','),
            'reference_number' => 'nullable|string|max:8',
            'lars_standard_code' => 'nullable|string|max:8',
            'otj_hours' => 'nullable|numeric|digits_between:1,3',
            'first_review' => 'nullable|numeric|digits_between:1,2',
            'review_frequency' => 'nullable|numeric|digits_between:1,2',
            'status' => 'required|numeric|in:0,1',
            'comments' => 'nullable|string|max:500',
            'sunesis_framework_id' => 'nullable|numeric',
            'leeway' => 'required|numeric',
        ];
    }
}
