<?php

namespace App\Http\Requests\Enrolment;

use App\Models\Lookups\UserTypeLookup;
use App\Models\Organisations\Location;
use App\Models\Organisations\Organisation;
use App\Models\Programmes\Programme;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreStep1Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('enrol-student');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $assessors = collect();
        $tutors = collect();
        $verifiers = collect();

        foreach(User::staffUsers()->withActiveAccess()->select(['id', 'user_type'])->get() as $user)
        {
            if ($user->user_type === UserTypeLookup::TYPE_ASSESSOR) 
            {
                $assessors->push($user);
            }
        
            if ($user->user_type === UserTypeLookup::TYPE_TUTOR) 
            {
                $tutors->push($user);
            }

            if ($user->user_type === UserTypeLookup::TYPE_VERIFIER) 
            {
                $verifiers->push($user);
            }
        }
        return [
            'programme_id' => 'required|numeric|in:' . Programme::active()->pluck('id')->implode(','),
            'start_date' => 'required|date',
            'planned_end_date' => 'required|date|after:start_date',
            'planned_end_date' => 'nullable|date|after:start_date',
            'employer_location' => 'required|numeric|in:' . Location::whereIn('org_locations.organisation_id', Organisation::employers()->pluck('orgs.id')->toArray())->pluck('org_locations.id')->implode(','),
            'primary_assessor' => 'required|in:' . $assessors->pluck('id')->implode(','),
            'secondary_assessor' => 'nullable|in:' . $assessors->pluck('id')->implode(','),
            'tutor' => 'nullable|in:' . $tutors->pluck('id')->implode(','),
            'verifier' => 'required|in:' . $verifiers->pluck('id')->implode(','),
        ];
    }

    public function attributes()
    {
        return [
            'employer_location' => 'Employer',
        ];
    }
}
