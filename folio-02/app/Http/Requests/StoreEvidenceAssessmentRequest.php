<?php

namespace App\Http\Requests;

use App\Models\Training\TrainingRecordEvidence;
use Illuminate\Foundation\Http\FormRequest;

class StoreEvidenceAssessmentRequest extends FormRequest
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
            $this->user()->can('assess-evidence');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'evidence_status' => 'required',
            'assessor_comments' => 'required',
            'evidence_categories' => 'nullable|array',
            'chkPC' => [
                'required_unless:evidence_status,!=,' . TrainingRecordEvidence::STATUS_ASSESSOR_REJECTED,
                'array', 
                'min:1'
            ],
        ];
    }

    public function messages()
    {
        return [
            'evidence_status.required' => 'Please provide the status of this evidence.',
            'assessor_comments.required' => 'Please provide your comments.',
            'chkPC.required' => 'You have not selected any performance criteria for this evidence .',
        ];
    }
}

