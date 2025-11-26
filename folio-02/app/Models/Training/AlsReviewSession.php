<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class AlsReviewSession extends Model
{
    protected $table = 'als_review_form_sessions';

    protected $guarded = [];

    protected $dates = [
        'session_date',
    ];

    public function alsReview()
    {
    	return $this->belongsTo(AlsReview::class, 'als_review_id');
    }

    const AlsSessionTypeAssessor = 'Assessor';
    const AlsSessionTypeTutor = 'Tutor';
}
