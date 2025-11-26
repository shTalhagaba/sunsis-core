<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganisationContactRequest extends FormRequest
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
        $organisation = request()->route('organisation');
        $locationIds = $organisation->locations()->pluck('id')->implode(',');

        return [
            'location_id' => 'required|numeric|in:' . $locationIds,
            'title' => 'required|max:8',
            'firstnames' => 'required|max:50',
            'surname' => 'required|max:50',
            'job_title' => 'nullable|max:50',
            'department' => 'nullable|max:50',
            'telephone' => 'required|max:20',
            'mobile' => 'nullable|max:20',
            'email' => 'nullable|email|max:150',
        ];
    }

    public function attributes()
    {
        return [
            'location_id' => 'Location',
        ];
    }
}
