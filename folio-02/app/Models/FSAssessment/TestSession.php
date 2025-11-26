<?php

namespace App\Models\FSAssessment;

use App\Models\Training\TrainingRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TestSession extends Model
{
    protected $fillable = ['tr_id', 'course_id', 'attempt_no', 'complete_by', 'score', 'started_at', 'completed_at', 'status', 'allocated_by'];

    protected $table = 'test_sessions';

    protected $casts = [
        'complete_by' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Status constants
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_NEEDS_REDO = 'needs_redo';
    public const STATUS_STARTED = 'started';
    public const STATUS_SUBMITTED = 'submitted';

    /**
     * Relationship: A test session belongs to a training record.
     */
    public function training()
    {
        return $this->belongsTo(TrainingRecord::class, 'tr_id');
    }

    /**
     * Relationship: A test session belongs to a course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relationship: A test session has multiple responses.
     */
    public function responses()
    {
        return $this->hasMany(LearnerResponse::class);
    }

    /**
     * Relationship: A test session has assessments.
     */
    public function assessments()
    {
        return $this->hasMany(TestSessionAssessment::class);
    }

    /**
     * Relationship: A question belongs to a user (creator).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'allocated_by');
    }

    public function canBeDeleted()
    {
        return in_array($this->status, [self::STATUS_PENDING]);
    }

    public function isStarted()
    {
        return in_array($this->status, [self::STATUS_STARTED]);
    }

    public function isPending()
    {
        return in_array($this->status, [self::STATUS_PENDING]);
    }

    public function isSubmitted()
    {
        return in_array($this->status, [self::STATUS_SUBMITTED]);
    }

    public function isApproved()
    {
        return in_array($this->status, [self::STATUS_APPROVED]);
    }

    public function isRedo()
    {
        return in_array($this->status, [self::STATUS_NEEDS_REDO]);
    }

    public function percentage()
    {
        $correct = (int) $this->responses()->correct()->count();
        $total = (int) $this->responses()->count();
        return $total === 0 ? 0 : round(($correct / $total) * 100);
    }
}