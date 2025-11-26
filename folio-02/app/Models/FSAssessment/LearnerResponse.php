<?php

namespace App\Models\FSAssessment;

use Illuminate\Database\Eloquent\Model;

class LearnerResponse extends Model
{
    protected $fillable = ['test_session_id', 'question_id', 'answer_text', 'is_correct', 'tr_id', 'answer_mcq_option_id'];

    /**
     * Relationship: A response belongs to a test session.
     */
    public function testSession()
    {
        return $this->belongsTo(TestSession::class);
    }

    /**
     * Relationship: A response belongs to a question.
     */
    public function question()
    {
        return $this->belongsTo(CourseQuestion::class);
    }

    /**
     * Evaluate answer correctness.
     */
    public function evaluate()
    {
        $this->is_correct = $this->question->isCorrectAnswer($this->answer_text);
        $this->save();
    }

    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }
}
