<?php

namespace App\Http\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreTrainingRecordEvidenceRequest extends FormRequest
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
            $this->user()->can('create-evidence');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'evidence_type' => 'required',
            'evidence_name' => 'required|min:10|max:250',
            'evidence_desc' => 'required|min:10|max:1500',
            'tr_dp_task_id' => 'nullable|numeric|exists:tr_tasks,id',
        ];

        // conditional rules
        if($this->user()->isStudent())
        {
            $rules['learner_declaration'] = 'required|in:1';
        }
        if($this->input('evidence_type') == 'rowEvidenceURL')
        {
            $rules['evidence_url'] = 'required|min:10|max:250';
        }
        if($this->input('evidence_type') == 'rowEvidenceRef')
        {
            $rules['evidence_ref'] = 'required|min:10|max:250';
        }
        if($this->input('evidence_type') == 'rowEvidenceFile')
        {
            $rules['evidence_file'] = 'required|array|min:1|max:8';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'evidence_ref' => 'Evidence Reference',
            'evidence_desc' => 'Evidence Description',
            'evidence_desc' => 'Evidence Description',
            'evidence_file' => 'Evidence File',
            'evidence_url' => 'Evidence URL',
            'evidence_name' => 'Evidence Name',
        ];
    }

    public function messages()
    {
        return [
            'evidence_file.*.required' => 'Please upload an evidence file',
            'evidence_file.*.mimes' => 'Only jpeg,png and bmp images are allowed',
            'evidence_file.*.max' => 'Sorry! Maximum allowed size for an image is 20MB',
        ];
    }
}
