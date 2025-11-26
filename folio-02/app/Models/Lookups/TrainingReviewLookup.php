<?php

namespace App\Models\Lookups;

use ReflectionClass;

class TrainingReviewLookup extends BaseLookup
{
    const TYPE_PROGRESS_REVIEW = 5;
    
    protected $guarded = [];

    protected $table = 'lookup_review_types';
}