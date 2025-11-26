<?php

namespace App\Models\FSAssessment;

use Illuminate\Database\Eloquent\Model;

class CourseQuestionOption extends Model
{
    protected $fillable = ['question_id', 'option_text', 'is_correct'];

    protected $table = 'fs_course_question_options';

    /**
     * Relationship: A question option belongs to a question.
     */
    public function question()
    {
        return $this->belongsTo(CourseQuestion::class);
    }

    public function isCorrect()
    {
        return $this->is_correct;
    }
}
