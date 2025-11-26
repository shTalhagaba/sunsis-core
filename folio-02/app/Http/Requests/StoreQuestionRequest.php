<?php

namespace App\Http\Requests;

use App\Models\FSAssessment\CourseQuestion;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
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
            // 'field' => 'required',
            'type' => 'required|string|in:' . implode(',', CourseQuestion::TYPE_VALUES),
            'question_text' => 'required|string|max:5000',
            'active' => 'nullable|in:0,1',
            'question_order' => 'nullable|numeric',
            'question_image' => [
                'nullable',
                'file',
                'max:2048',
                'mimetypes:image/jpeg,image/png,image/gif',
                'mimes:jpeg,jpg,png',
            ],

        ];
    }

    public function prepareForValidation()
    {
        if ($this->input('type') !== CourseQuestion::TYPE_MCQ) 
        {
            $this->request->remove('options'); 
        }
    }

}
