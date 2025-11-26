<?php

namespace App\Models\Training;

use App\Traits\Filterable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Otj extends Model implements HasMedia, Auditable
{
    use HasMediaTrait, \OwenIt\Auditing\Auditable, Filterable;

    protected $table = 'otj';

    protected $guarded = [];

    public function training()
    {
        return $this->belongsTo(TrainingRecord::class, 'tr_id');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    public function scopeIsOtj($query)
    {
        return $query->where('is_otj', true);
    }

    public function scopeAwaiting($query)
    {
        return $query->where('status', self::STATUS_AWAITING);
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', self::STATUS_SUBMITTED);
    }

    public function isAccepted()
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isEditable()
    {
        return !$this->isAccepted() || auth()->user()->isAssessor();
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($otj) {
            $otj->media()->each(function ($media) {
                $media->delete();
            });
        });
    }

    const STATUS_ACCEPTED  = 'Accepted';
    const STATUS_AWAITING  = 'Awaiting';
    const STATUS_SUBMITTED = 'Submitted';
    const STATUS_REFERRED = 'Referred';
}
