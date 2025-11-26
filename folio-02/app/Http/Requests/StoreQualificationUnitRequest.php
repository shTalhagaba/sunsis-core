<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQualificationUnitRequest extends FormRequest
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
        $qualification = request()->route('qualification');
        $unit = request()->route('unit');

        $rules = [
            'title' => 'required|max:850',
            'unit_group' => 'required|numeric|in:1,2',
            'glh' => 'required|numeric|min:0',
            'unit_credit_value' => 'required|numeric|min:0',
            'learning_outcomes' => 'nullable|string',
            'number_of_pcs' => 'nullable|numeric',
        ];

        if($this->method() == 'POST')
        {
            $rules = array_merge($rules, [
                'unique_ref_number' => [
                    'required',
                    'string',
                    'max:15',
                    Rule::unique('qualification_units')->where(function ($query) use ($qualification) {
                        return $query->where('qualification_id', $qualification->id);
                    })
                ],
                'unit_owner_ref' => [
                    'required',
                    'string',
                    'max:15',
                    Rule::unique('qualification_units')->where(function ($query) use ($qualification) {
                        return $query->where('qualification_id', $qualification->id);
                    })
                ],
            ]);
        }
        else
        {
            $rules = array_merge($rules, [
                'unique_ref_number' => 'required|max:15|unique:qualification_units,unique_ref_number,'.$unit->id.',id,qualification_id,'.$qualification->id,
                'unit_owner_ref' => 'required|max:15|unique:qualification_units,unit_owner_ref,'.$unit->id.',id,qualification_id,'.$qualification->id,
            ]);            
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'unique_ref_number' => 'Unique Reference',
            'unit_owner_ref' => 'Onwer Reference',
        ];
    }
}
