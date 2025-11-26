<?php

namespace App\Http\Requests;

use App\Rules\DateBetween;
use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingPortfolioRequest extends FormRequest
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
        $training = request()->route('training');
        $qualifications = request()->input('qualifications');
        $rules = [
            'training_id' => 'required|numeric|in:' . $training->id,
            'qualifications' => 'required|array|min:1',
            'chkUnit' => 'required|array|min:1',
        ];

        if(is_array($qualifications))
        {
            foreach($qualifications AS $qualificationId)
            {
                $rules = $rules+[
                    'start_date_qual_' . $qualificationId => ['required', 'date', new DateBetween($training->start_date, $training->planned_end_date)],
                    'planned_end_date_qual_' . $qualificationId => ['required', 'date', new DateBetween($training->start_date, $training->planned_end_date)],
                ];
            }
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'start_date_qual_.*' => 'Start date of selected qualification(s)',
            'planned_end_date_qual_.*' => 'Planned end date of selected qualification(s)',
        ];
    }

    public function messages()
    {
        return [
            'qualifications.*' => 'You have not selected any qualification',
            'chkUnit.*' => 'You have not selected any unit',
        ];
    }
}
