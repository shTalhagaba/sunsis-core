<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganisationLocationRequest extends FormRequest
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
            $this->user()->can('update-employer-organisation') 
            ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'is_legal_address' => 'nullable|numeric|in:0,1',
            'title' => 'required|min:3|max:50',
            'address_line_1' => 'required|max:70',
            'address_line_2' => 'nullable|max:70',
            'address_line_3' => 'nullable|max:70',
            'address_line_4' => 'nullable|max:70',
            'telephone' => 'nullable|max:20',
            'mobile' => 'nullable|max:20',
            'fax' => 'nullable|max:20',
            'postcode' => [
                'nullable',
                function($attribute, $value, $fail)
                {
                    if(! \Postcode::validate($value))
                    {
                        $fail($attribute.' is invalid');
                    }
                }
            ],
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'Location title',
        ];
    }
}
