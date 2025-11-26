<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreTrainingReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isActive();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $training = request()->route('training');
        $assessorIds[] = $training->primary_assessor;
        if(!is_null($training->secondary_assessor))
        {
            $assessorIds[] = $training->secondary_assessor;
        }

        return [
            'title' => 'required|max:100',
            'assessor' => 'required|numeric|in:' . implode(',', $assessorIds),
            'due_date' => 'required|date',
            'meeting_date' => 'nullable|date',
            'start_time' => 'required|date_format:"H:i"',
            'end_time' => 'required|date_format:"H:i"|after:start_time',
            'type_of_review' => 'required|numeric|in:' . DB::table('lookup_review_types')->pluck('id')->implode(','),
            'portfolio_id' => 'nullable|numeric|in:' . $training->portfolios()->pluck('id')->implode(','),
            'comments' => 'nullable|max:500',
        ];
    }

    public function attributes()
    {
        return [
            'due_date' => 'Scheduled Date',
            'portfolio_id' => 'Portfolio',
        ];
    }
}
