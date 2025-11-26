<?php

namespace App\Models\Tags;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = [
        'name',
        'type',
        'color',
        'order_column',
    ];

    public function taggable(): MorphTo
    {
        return $this->morphTo();
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}
