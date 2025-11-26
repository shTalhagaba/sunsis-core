<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MediaSection extends Model
{
    protected $table = 'media_sections';

    protected $fillable = [
        'name',
        'type',
        'slug',
        'order_column'
    ];

    public function model(): MorphTo
    {
      return $this->morphTo();
    }
}
