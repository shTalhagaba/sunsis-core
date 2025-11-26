<?php

namespace App\Http\Requests;

use App\Models\Training\PortfolioUnitIqa;
use Illuminate\Foundation\Http\FormRequest;

class StoreEvidenceIqaRequest extends FormRequest
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
            $this->user()->can('iqa-assessment');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'iqa_status' => 'required|numeric|in:0,'.PortfolioUnitIqa::STATUS_IQA_ACCEPTED.','.PortfolioUnitIqa::STATUS_IQA_REFERRED,
            'verifier_comments' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'iqa_status' => 'IQA Status',
            'verifier_comments' => 'IQA Comments',
        ];
    }
}
