<?php

namespace App\Models\Training;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AlsAssessmentPlan extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'als_assessment_plans';

    protected $guarded = [];

    protected $dates = [
        'fsf_date_1',
        'fsf_date_2',
        'fsf_date_3',
        'fsf_date_4',
        'fsf_date_5',
        'fsfr_date',
        'learner_sign_date',
        'assessor_sign_date',
        'fs_tutor_sign_date',
        'iqa_sign_date',
        'als_tutor_sign_date',
        'als_meeting_date',
        'referral_date',
    ];

    public function training()
    {
    	return $this->belongsTo(TrainingRecord::class, 'tr_id');
    }

    public function assessorName()
    {
        return $this->assessor_id ? optional(User::find($this->assessor_id))->full_name : '';
    }

    public function tutorName()
    {
        return $this->fs_tutor_id ? optional(User::find($this->fs_tutor_id))->full_name : '';
    }

    public function iqaName()
    {
        return $this->iqa_id ? optional(User::find($this->iqa_id))->full_name : '';
    }

    public function alsTutorName()
    {
        return $this->als_tutor_id ? optional(User::find($this->als_tutor_id))->full_name : '';
    }

    public function locked()
    {
        return $this->learner_sign ||
            $this->assessor_sign ||
            $this->fs_tutor_sign ||
            $this->iqa_sign ||
            $this->als_tutor_sign;
    }

    public function isAllowed($userId)
    {
        if($userId == $this->assessor_id && $this->assessor_sign)
        {
            return false;
        }
        if($userId == $this->fs_tutor_id && $userId == $this->als_tutor_id && $this->fs_tutor_sign && $this->als_tutor_sign)
        {
            return false;
        }
        if($userId == $this->fs_tutor_id && $this->fs_tutor_sign)
        {
            return false;
        }
        if($userId == $this->als_tutor_id && $this->als_tutor_sign)
        {
            return false;
        }
        if($userId == $this->iqa_id && $this->iqa_sign)
        {
            return false;
        }
        return in_array($userId, [$this->assessor_id, $this->fs_tutor_id, $this->iqa_id, $this->als_tutor_id]);
    }
}
