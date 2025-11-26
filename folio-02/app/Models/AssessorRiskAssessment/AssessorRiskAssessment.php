<?php

namespace App\Models\AssessorRiskAssessment;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AssessorRiskAssessment extends Model
{
    protected $table = 'assessor_risk_assessments';

    protected $guarded = [];

    protected $casts = [
        'date_of_observation' => 'date',
        'date_of_last_observation' => 'date',
    ];

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function getOverallGrade($totalScore)
    {
        if ($totalScore <= 10) 
        {
            return 1;
        } 
        elseif ($totalScore <= 20) 
        {
            return 2;
        } 
        elseif ($totalScore <= 30) 
        {
            return 3;
        } 
        elseif ($totalScore <= 40) 
        {
            return 4;
        }
        else 
        {
            return 'Invalid Score';
        }
    }

    public function getRGB()
    {
        $grade = self::getOverallGrade($this->total_score ?? 0);
        if($grade === 1)
        {
            return 'Green';
        }
        elseif($grade === 2)
        {
            return 'Amber';
        }
        elseif($grade >= 3)
        {
            return 'Red';
        }
        else
        {
            return '';
        }
    }

    public function overallGradePercentage()
    {
        $grade = self::getOverallGrade($this->total_score ?? 0);

        switch ($grade) {
            case 1:
                return '20%';
            case 2:
                return '30%';
            case 3:
                return '60%';
            case 4:
                return '100%';
            default:
                return 'Invalid Grade';
        }
    }

    public static function getGradesList()
    {
        $grades = [
            '' => 'Select Grade',
            '1' => '1 (10%)',
            '2' => '2 (30%)',
            '3' => '3 (60%)',
            '4' => '4 (100%)',
        ];

        return $grades;
    }

    public function isCompleted()
    {
        return $this->completed;
    }
}