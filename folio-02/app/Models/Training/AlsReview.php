<?php

namespace App\Models\Training;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use OwenIt\Auditing\Contracts\Auditable;

class AlsReview extends Model implements HasMedia, Auditable
{
    use HasMediaTrait, \OwenIt\Auditing\Auditable;

    protected $table = 'als_reviews';

    protected $guarded = [];

    protected $dates = [
        'planned_date',
        'date_of_review',
        'learner_sign_date',
        'assessor_sign_date',
        'tutor_sign_date',
    ];

    public function training()
    {
    	return $this->belongsTo(TrainingRecord::class, 'tr_id');
    }

    public function sessions()
    {
    	return $this->hasMany(AlsReviewSession::class, 'als_review_id');
    }

    public function scopeAlsSessions($query, $sessionType)
    {
        return $this->sessions()->where('session_type', $sessionType)->get();
    }

    public function lastReview()
    {
        return self::where('tr_id', $this->tr_id)
            ->whereDate('planned_date', '<', $this->planned_date)
            ->whereNotNull('date_of_review')
            ->orderBy('planned_date', 'DESC')
            ->first();
    }

    public function assessorName()
    {
        return $this->assessor ? optional(User::find($this->assessor))->full_name : '';
    }

    public function tutorName()
    {
        return $this->tutor ? optional(User::find($this->tutor))->full_name : '';
    }

    public function locked()
    {
        return $this->learner_sign || $this->assessor_sign || $this->tutor_sign;
    }

    public function readyToSignForLearner()
    {
        return !$this->learner_sign && ($this->assessor_sign || $this->tutor_sign);
    }

    public static function boot()
    {
        parent::boot();

        self::deleting(function ($review) {
            $review->sessions()->each(function ($session) {
                $session->delete();
            });

            $review->media()->each(function ($media) {
                $media->delete();
            });
        });
    }
}
