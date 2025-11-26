<?php

namespace App\Models\CRM;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Carbon\Carbon;

class CrmNote extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $table = 'crm_notes';

    protected $guarded = [];

    protected $dates = [
        'date_of_contact',
    ];

    protected $casts = [
        'time_of_contact' => 'time',
    ];

    public function noteable(): MorphTo
    {
        return $this->morphTo();
    }

    public function setTimeOfContactAttribute($value)
    {
        $this->attributes['time_of_contact'] = Carbon::parse($value)->format('H:i:s');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($crmNote) {
            $crmNote->media()->each(function ($media) {
                $media->delete();
            });
        });
    }
}
