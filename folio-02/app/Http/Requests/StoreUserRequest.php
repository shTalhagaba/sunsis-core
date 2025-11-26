<?php

namespace App\Http\Requests;

use App\Models\Lookups\UserTypeLookup;
use App\Rules\UsernameRule;
use Illuminate\Foundation\Http\FormRequest;
use Postcode;

class StoreUserRequest extends FormRequest
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
            ($this->user()->can('create-system-user') || $this->user()->can('update-system-user'));
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
            'gender' => 'nullable|in:M,F,NB,U,SELF|max:50',
            'gender_self_describe' => 'nullable|string|max:40',
            'primary_email' => 'required|email|max:191',
            'secondary_email' => 'nullable|email|max:191',
            'fb_id' => 'nullable|string|max:255',
            'twitter_handle' => 'nullable|string|max:255',
            'web_access' => 'nullable',
            'send_login_details' => 'nullable',
            'user_type' => 'required|numeric|in:' . UserTypeLookup::where('id', '!=', UserTypeLookup::TYPE_STUDENT)->pluck('id')->implode(','),
            'assessor_type' => 'nullable',
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
            ],
            'employer_location' => 'nullable|numeric',
            'rag_rating' => 'nullable',
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
