<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Postcode;

class StoreProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isActive() && auth()->user()->id === $this->user()->id;
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
            'primary_email' => 'required|email|max:191',
            'secondary_email' => 'nullable|email|max:191',
            'fb_id' => 'nullable|string|max:255',
            'twitter_handle' => 'nullable|string|max:255',
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
            'avatar' => 'mimes:jpeg,jpg,png,bmp,gif,svg|max:2048',
        ];

        return $rules;
    }
}
