<?php

namespace App\Models\Training;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class DeepDive extends Model
{
    protected $table = 'deep_dives';

    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deep_dive_date',
    ];

    public function training()
    {
        return $this->belongsTo(TrainingRecord::class, 'tr_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function primaryAssessor()
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    public function verifierUser()
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }

    public function secondaryAssessor()
    {
        return $this->belongsTo(User::class, 'secondary_assessor_id');
    }

    public function employerUser()
    {
        return $this->belongsTo(User::class, 'employer_user_id');
    }

    public function operationsManager()
    {
        return $this->belongsTo(User::class, 'ops_manager_id');
    }

    public function employerMentor()
    {
        return $this->belongsTo(User::class, 'employer_user_id');
    }

    public function getOverallRagRatingAttribute()
    {
        $targetProgress = $this->target_progress;
        $actualProgress = $this->actual_progress;

        if ($targetProgress == 0) {
            return ''; 
        }

        // Percentage difference relative to target
        $percentBehind = (($targetProgress - $actualProgress));

        if ($actualProgress >= $targetProgress) {
            return 'Green'; // On or above target
        } elseif ($percentBehind <= 10) {
            return 'Amber'; // Within 10% behind target
        } else {
            return 'Red';   // More than 10% behind target
        }
    }
}
