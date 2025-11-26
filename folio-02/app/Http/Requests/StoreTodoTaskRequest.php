<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;

class StoreTodoTaskRequest extends FormRequest
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
        $relatedLearners = '';
        if($this->user()->isAssessor())
        {
            $relatedLearners = Student::orderBy('firstnames')
                ->whereHas('training_records', function($q){
                    $q->where('tr.primary_assessor', '=', $this->user()->id)
                        ->orWhere('tr.secondary_assessor', '=', $this->user()->id);
                })
                ->get('id')
                ->pluck('id')
                ->implode(',');
        }
        elseif($this->user()->isTutor())
        {
            $relatedLearners = Student::orderBy('firstnames')
                ->whereHas('training_records', function($q){
                    $q->where('tr.tutor', '=', $this->user()->id);
                })                
                ->get('id')
                ->pluck('id')
                ->implode(',');
        }

        return [
            'title' => 'required|string|max:70',
            'description' => 'nullable|string|max:255',
            'belongs_to' => 'nullable|numeric',
            'created_by' => 'nullable|numeric',
            'completed' => 'nullable|boolean',
            'belongs_to' => 'nullable|numeric|in:' . $relatedLearners,
        ];
    }

    public function attributes()
    {
        return [
            'belongs_to' => 'student',
        ];
    }
}
