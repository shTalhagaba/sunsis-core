<?php

namespace App\Http\Requests;

use App\Models\Lookups\UserTypeLookup;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;


class StoreTrainingRecordRequest extends FormRequest
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
        $assessors = User::where('user_type', UserTypeLookup::TYPE_ASSESSOR)->pluck('id');
        
        return [
            // 'status_code' => 'required|numeric|in:' . TrainingStatusLookup::pluck('id')->implode(','),
            // 'start_date' => 'required|date',
            // 'planned_end_date' => 'required|date|after:start_date',
            // 'actual_end_date' => 'nullable|date|after:start_date',
            'employer_location' => 'required',
            'primary_assessor' => 'required|numeric|in:' . $assessors->implode(','),
            'secondary_assessor' => 'nullable|numeric|in:' . $assessors->implode(','),
            'tutor' => 'nullable|numeric|in:' . User::where('user_type', UserTypeLookup::TYPE_TUTOR)->pluck('id')->implode(','),
            'verifier' => 'required|numeric|in:' . User::where('user_type', UserTypeLookup::TYPE_VERIFIER)->pluck('id')->implode(','),
            'otj_hours' => 'nullable|numeric',
            'contracted_hours_per_week' => 'nullable|between:0,99.00',
            'weeks_to_worked_per_year' => 'nullable|between:0,50.00',
        ];
    }

    public function messages()
    {
        return [
            'employer_location.required' => 'The employer field cannot be left blank.',
            'start_date*.date_format' => 'Some start date fields are not in the required format (dd/mm/yyyy).',
            'start_date*.required' => 'Some start date fields are not provided.',
            'planned_end_date*.date_format' => 'Some planned end date fields are not in the required format (dd/mm/yyyy).',
            'planned_end_date*.required' => 'Some planned end date fields are not provided.',
            'ab_registration_date*.date_format' => 'Some awarding body registration date fields are not in the required format (dd/mm/yyyy).',
            'actual_end_date*.date_format' => 'Some actual end date fields are not in the required format (dd/mm/yyyy).',
            'cert_applied*.date_format' => 'Some certificate applied date fields are not in the required format (dd/mm/yyyy).',
            'cert_received*.date_format' => 'Some certificate received date fields are not in the required format (dd/mm/yyyy).',
            'cert_sent_to_learner*.date_format' => 'Some certificate sent to learner date fields are not in the required format (dd/mm/yyyy).',
            'ab_registration_number*.max' => 'Awarding body registration number must not exceed 15 characters.',
        ];
    }

    // Add dynamic validation for fields like start_date1, ab_registration_date1, etc.
    public function withValidator(Validator $validator)
    {
        foreach ($this->request->all() as $key => $value) 
        {
            if (
                preg_match('/^start_date\d+$/', $key) 
                || preg_match('/^planned_end_date\d+$/', $key) 
            ) 
            {
                $validator->addRules([
                    $key => 'required|date_format:"Y-m-d"',  
                ]);
            }

            if (
                preg_match('/^actual_end_date\d+$/', $key) 
                || preg_match('/^ab_registration_date\d+$/', $key) 
                || preg_match('/^planned_end_date\d+$/', $key) 
                || preg_match('/^cert_applied\d+$/', $key)  
                || preg_match('/^cert_received\d+$/', $key) 
                || preg_match('/^cert_sent_to_learner\d+$/', $key) 
            ) 
            {
                $validator->addRules([
                    $key => 'nullable|date_format:"Y-m-d"',  
                ]);
            }

            if (preg_match('/^ab_registration_number\d+$/', $key)) 
            {
                $validator->addRules([
                    $key => 'nullable|string|max:15',  
                ]);
            }
        }
    }
}
