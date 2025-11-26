<?php

namespace App\Models\LearningResources;

use Illuminate\Database\Eloquent\Model;

class LearningResourceUser extends Model
{
    protected $table = 'learning_resource_user';

    protected $fillable = [
        'user_id',
        'learning_resource_id',
        'liked',
        'bookmarked',
    ];

}
