<?php

namespace App\Models\FSAssessment;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Models\FSAssessment\Course;
use App\Models\User;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CourseQuestion extends Model implements HasMedia
{
    use Filterable, HasMediaTrait;
    
    protected $fillable = ['question_order', 'type', 'question_text', 'correct_answer', 'acceptable_answers', 'file_name', 'created_by', 'active'];

    protected $table = 'fs_course_questions';

    public const TYPE_MCQ = 'multiple_choice';
    public const TYPE_DESCRIPTIVE = 'descriptive';
    public const TYPE_VALUES = [
        'multiple_choice',
        'descriptive'
    ];

    protected $casts = [
        'acceptable_answers' => 'array',
    ];

    /**
     * Relationship: A question can have multiple options (if multiple-choice).
     */
    public function question_options()
    {
        return $this->hasMany(CourseQuestionOption::class, 'question_id');
    }

    /**
     * Relationship: A question can belong to multiple courses.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Relationship: A question belongs to a user (creator).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if an answer is correct.
     */
    public function isCorrectAnswer($answer): bool
    {
        if($this->isDescriptive())
        {
            // $answer is a string
            return trim(strtolower($answer)) === trim(strtolower($this->correct_answer)) ||
                in_array(trim(strtolower($answer)), array_map('strtolower', $this->acceptable_answers ?? []));
        }

        if($this->isMcq())
        {
            // $answer is option id
            return (int) $answer === $this->question_options()->where('is_correct', 1)->first()->id;
        }
    }

    public function isMcq()
    {
        return $this->type === self::TYPE_MCQ;
    }

    public function isDescriptive()
    {
        return $this->type === self::TYPE_DESCRIPTIVE;
    }

    public function getAcceptableAnswers()
    {
        return !$this->isDescriptive() ? [] : $this->acceptable_answers;
    }

    public function getImageSrc()
    {
        if (is_null($this->file_name)) 
        {
            return asset('images/default-placeholder.jpg');
        }

        // Cache the signed URL for 10 minutes
        return Cache::remember("question_image_{$this->id}", now()->addMinutes(10), function () {
            $mediaItem = $this->getFirstMedia('question_image'); 
            return $mediaItem 
                ? Storage::disk('s3')->temporaryUrl($mediaItem->getPath(), now()->addMinutes(10)) 
                : asset('images/default-placeholder.jpg');
        });
    }

    public function isActive()
    {
        return $this->active;
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($question) {
            $question->question_options()->each(function ($option) {
                $option->delete();
            });
            $question->media()->each(function ($media) {
                $media->delete();
            });
        });
    }
}
