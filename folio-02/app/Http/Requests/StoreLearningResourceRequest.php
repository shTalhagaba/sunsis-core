<?php

namespace App\Http\Requests;

use App\Models\Lookups\LearningResourceTypeLookup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreLearningResourceRequest extends FormRequest
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
            $this->user()->isStaff();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'resource_type' => 'required|in:' . implode(',', array_keys(LearningResourceTypeLookup::getSelectData())),
            'resource_name' => 'required|string|min:10|max:100',
            'resource_short_description' => 'required|string|min:10|max:1000',
            'resource_content' => [
                'required_if:resource_type,' . LearningResourceTypeLookup::TYPE_TEXT,
            ],
            'resource_url' => [
                'required_if:resource_type,' . LearningResourceTypeLookup::TYPE_URL,
                'max:2048',
            ],
        ];
    }

    protected function withValidator(Validator $validator)
    {
        $validator->sometimes('resource_content', 'string', function ($input) {
            return $input->resource_type == LearningResourceTypeLookup::TYPE_TEXT;
        });

        $validator->sometimes('resource_url', 'url', function ($input) {
            return $input->resource_type == LearningResourceTypeLookup::TYPE_URL;
        });
    }

    public function messages()
    {
        return [
            'resource_url.required_if' => 'The resource URL field is required when resource type is URL.',
            'resource_content.required_if' => 'The resource content is required when resource type is Text Document.',
            
        ];
    }
}
