<?php

namespace App\Http\Requests;

use App\Models\Lookups\EthnicityLookup;
use App\Models\Lookups\UserTypeLookup;
use App\Rules\NationalInsuranceRule;
use App\Rules\UniqueLearnerNumberRule;
use App\Rules\UsernameRule;
use Illuminate\Foundation\Http\FormRequest;
use Postcode;

class StoreStudentRequest extends FormRequest
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
            ($this->user()->can('create-student') || $this->user()->can('update-student'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'firstnames' => 'required|min:3|max:50',
            'surname' => 'required|min:3|max:50',
            'gender' => 'nullable|in:M,F,NB,U,SELF',
            'date_of_birth' => 'nullable|date_format:"Y-m-d"',
            'ethnicity' => 'nullable|numeric|in:' . EthnicityLookup::pluck('id')->implode(','),
            'ni' => [
                'nullable',
                'string',
                new NationalInsuranceRule(),
            ],
            'uln' => [
                'nullable',
                'min:10',
                'max:10',
                new UniqueLearnerNumberRule(),
            ],
            'primary_email' => 'required|email|max:191',
            'secondary_email' => 'nullable|email|max:191',
            'fb_id' => 'nullable|string|max:255',
            'twitter_handle' => 'nullable|string|max:255',
            'web_access' => 'nullable',
            'send_login_details' => 'nullable',
            'user_type' => 'required|numeric|in:' . UserTypeLookup::TYPE_STUDENT,
            'employer_location' => 'required|numeric',
            'work_address_line_1' => 'nullable|max:50',
            'work_address_line_2' => 'nullable|max:50',
            'work_address_line_3' => 'nullable|max:50',
            'work_address_line_4' => 'nullable|max:50',
            'work_telephone' => 'nullable|max:20',
            'work_mobile' => 'nullable|max:20',
            'work_postcode' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (! Postcode::validate($value)) {
                        $fail($attribute . ' is invalid');
                    }
                }
            ],
            'home_address_line_1' => 'nullable|max:50',
            'home_address_line_2' => 'nullable|max:50',
            'home_address_line_3' => 'nullable|max:50',
            'home_address_line_4' => 'nullable|max:50',
            'home_telephone' => 'nullable|max:20',
            'home_mobile' => 'nullable|max:20',
            'home_postcode' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (! Postcode::validate($value)) {
                        $fail($attribute . ' is invalid');
                    }
                }
            ]
        ];

        if ($this->method() == 'POST') {
            $rules = array_merge($rules, [
                'username' => [
                    'required',
                    'string',
                    new UsernameRule,
                ]
            ]);
        }

        return $rules;
    }
}
