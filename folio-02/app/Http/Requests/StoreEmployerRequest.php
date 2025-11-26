<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreEmployerRequest extends FormRequest
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
            ($this->user()->can('create-employer-organisation') || $this->user()->can('update-employer-organisation'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = request()->route('employer');

        return [
            'active' => 'required|numeric|in:0,1',
            'legal_name' => 'required|min:3|max:191',
            'trading_name' => 'required|min:3|max:191',
            'company_number' => 'nullable|max:12',
            'vat_number' => 'nullable|max:12',
            'edrs' => [
                'nullable',
                Rule::unique('orgs', 'edrs')->where(function ($query) use ($id) {
                    return $query->where('edrs', '<>', 999999999)
                        ->orWhere(function ($query) use ($id) {
                            $query->where('id', $id)
                                ->where('edrs', 999999999);
                        });
                })->ignore($id)
            ],
            'sector' => 'nullable|numeric|in:' . DB::table('lookup_org_sectors')->pluck('id')->implode(','),
            'title' => 'required|min:3|max:50',
            'address_line_1' => 'required|max:70',
            'address_line_2' => 'nullable|max:70',
            'address_line_3' => 'nullable|max:70',
            'address_line_4' => 'nullable|max:70',
            'telephone' => 'nullable|max:20',
            'mobile' => 'required|max:20',
            'fax' => 'nullable|max:20',
            'email' => 'required|email',
            'postcode' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (! \Postcode::validate($value)) {
                        $fail($attribute . ' is invalid');
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