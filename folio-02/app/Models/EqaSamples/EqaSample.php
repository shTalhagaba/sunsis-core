<?php

namespace App\Models\EqaSamples;

use App\Models\Training\TrainingRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EqaSample extends Model
{
    protected $table = 'eqa_samples';

    protected $guarded = [];

    /**
     * The training records that belong to the sample.
     */
    public function training_records()
    {
        return $this->belongsToMany(TrainingRecord::class, 'eqa_samples_trs', 'sample_id', 'tr_id');
    }

    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    /**
     * The training records that belong to the sample.
     */
    public function eqa_personnels()
    {
        return $this->belongsToMany(User::class, 'eqa_samples_personnels', 'sample_id', 'eqa_user_id');
    }

    public function scopeFilter($query, $filters)
    {
        if(isset($filters['keyword']))
        {
            $query->where('title', 'LIKE', '%' . $filters['keyword'] . '%');
        }
    }
}
