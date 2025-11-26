<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use OwenIt\Auditing\Contracts\Auditable;

class TrainingReview extends Model implements HasMedia, Auditable
{
    use HasMediaTrait, \OwenIt\Auditing\Auditable;

    protected $table = 'training_reviews';

    protected $guarded = [];

    protected $dates = [
        'due_date',
        'meeting_date',
    ];

    public function training()
    {
        return $this->belongsTo(TrainingRecord::class, 'tr_id');
    }

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class, 'portfolio_id');
    }

    public function form()
    {
        return $this->hasOne(TrainingReviewForm::class, 'review_id');
    }

    public function lastReview()
    {
        return self::where('tr_id', $this->tr_id)
            ->whereDate('due_date', '<', $this->due_date)
            ->whereNotNull('meeting_date')
            ->orderBy('due_date', 'DESC')
            ->first();
    }

    public function daysSinceTrainingStart()
    {
        return $this->meeting_date ?
            $this->meeting_date->diffInDays($this->training->start_date) :
            now()->diffInDays($this->training->start_date);
    }

    public function scheduled()
    {
        return $this->due_date->isFuture() && !optional($this->form)->completed();
    }

    public function overdue()
    {
        return $this->due_date->isPast() && !optional($this->form)->completed();
    }

    public function completed()
    {
        return optional($this->form)->completed();
    }

    public function scopeOverdueReview(Builder $query)
    {
        return $query
            ->where('due_date', '<', now())
            ->where('end_time', '<=', now())
            ->whereDoesntHave('form');
    }

    public static function boot()
    {
        parent::boot();

        self::deleting(function ($review) {
            $review->form()->each(function ($form) {
                $form->delete();
            });

            $review->media()->each(function ($media) {
                $media->delete();
            });
        });
    }

    const REVIEW_TYPES = [
        1 => 'Face-to-face',
        2 => 'Telephone',
        3 => 'Workplace',
        4 => 'Formal Review',
        5 => 'Progress Review',
    ];
}
