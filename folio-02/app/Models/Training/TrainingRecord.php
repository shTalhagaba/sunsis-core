<?php

namespace App\Models\Training;

use App\Helpers\AppHelper;
use App\Models\CRM\CrmNote;
use App\Models\FSAssessment\TestSession;
use App\Models\IQA\FourWeekAudit;
use App\Models\Lookups\TrainingStatusLookup;
use App\Models\User;
use App\Models\Programmes\Programme;
use Carbon\Carbon;
use App\Models\Organisations\Location;
use App\Models\Tags\Tag;
use App\Traits\Filterable;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use App\Models\MediaSection;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Builder;

class TrainingRecord extends Model implements Auditable, HasMedia
{
    use \OwenIt\Auditing\Auditable, Filterable, HasMediaTrait;

    protected $table = 'tr';

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $auditExclude = [
        'system_ref',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_date',
        'planned_end_date',
        'actual_end_date',
        'epa_date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class, 'programme_id');
    }

    public function trainingStatus()
    {
        return $this->hasOne(TrainingStatusLookup::class, 'id', 'status_code');
    }

    public function sessions()
    {
        return $this->hasMany(TrainingDeliveryPlanSession::class, 'tr_id')->orderBy('session_start_date');
    }

    public function training_plans()
    {
        return $this->hasMany(StudentTrainingPlan::class, 'tr_id');
    }

    public function otj()
    {
        return $this->hasMany(Otj::class, 'tr_id');
    }

    public function statusChanges()
    {
        return $this->hasMany(TrainingStatusChangeLog::class, 'tr_id');
    }

    public function latestStatusChange()
    {
        return $this->statusChanges()->latest()->first();
    }

    public function reviews()
    {
        return $this->hasMany(TrainingReview::class, 'tr_id');
    }

    public function deepDives()
    {
        return $this->hasMany(DeepDive::class, 'tr_id');
    }

    public function alsReviews()
    {
        return $this->hasMany(AlsReview::class, 'tr_id');
    }

    public function alsAssessmentPlan()
    {
        return $this->hasOne(AlsAssessmentPlan::class, 'tr_id');
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class, 'tr_id')->orderBy('sequence');
    }

    public function units()
    {
        return $this->hasManyThrough(PortfolioUnit::class, Portfolio::class, 'tr_id', 'portfolio_id', 'id', 'id');
    }

    public function evidences()
    {
        return $this->hasMany(TrainingRecordEvidence::class, 'tr_id');
    }

    public function four_week_audit()
    {
        return $this->hasOne(FourWeekAudit::class, 'tr_id');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')
            ->where('type', 'TrainingRecord')
            ->orderBy('order_column');
    }

    public function crmNotes()
    {
        return $this->morphMany(CrmNote::class, 'noteable');
    }

    public function getMonthsElapsedAttribute()
    {
        $start_date = $this->getOriginal('start_date');
        $start_date = Carbon::parse($start_date);
        $today = \Carbon\Carbon::today();
        return $start_date->diffInMonths($today);
    }

    public function getTotalMonthsAttribute()
    {
        $start_date = $this->getOriginal('start_date');
        $start_date = Carbon::parse($start_date);
        $planned_end_date = $this->getOriginal('planned_end_date');
        $planned_end_date = Carbon::parse($planned_end_date);
        return $start_date->diffInMonths($planned_end_date);
    }

    public function employer()
    {
        return $this->hasOneThrough(
            'App\Models\Organisations\Organisation',
            'App\Models\Organisations\Location',
            'id', // Foreign key on org_locations table...
            'id', // Foreign key on orgs table...
            'employer_location', // Local key on users table...
            'organisation_id' // Local key on org_locations table...
        );
    }

    public function mediaSections()
    {
        return $this->morphToMany(
            MediaSection::class,
            'model',
            'media_section_has_models',
            'model_id',
            'section_id'
        )
            ->where('media_sections.type', 'TrainingRecord')
            ->orderBy('media_sections.name');
    }

    public function primaryAssessor()
    {
        return $this->hasOne(User::class, 'id', 'primary_assessor');
    }

    public function secondaryAssessor()
    {
        return $this->hasOne(User::class, 'id', 'secondary_assessor');
    }

    public function verifierUser()
    {
        return $this->hasOne(User::class, 'id', 'verifier');
    }

    public function tutorUser()
    {
        return $this->hasOne(User::class, 'id', 'tutor');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'employer_location');
    }

    public function scopeCaseloadCondition(Builder $query, $user)
    {
        return AppHelper::addCaseloadConditionEloquent($query, $user);
    }

    public function contrcatedHoursPerYear()
    {
        return AppHelper::calculateContrcatedHoursPerYear($this->contracted_hours_per_week, $this->weeks_to_worked_per_year);
    }

    public function totalContrcatedHours()
    {
        return AppHelper::calculateTotalContrcatedHours($this->start_date, $this->planned_end_date, $this->contracted_hours_per_week);
    }

    public function otjHours()
    {
        return AppHelper::calculateOtjHours($this->start_date, $this->planned_end_date, $this->contracted_hours_per_week);
    }

    public function fsTestSessions()
    {
        return $this->hasMany(TestSession::class, 'tr_id');
    }

    public function durationInDays()
    {
        return $this->planned_end_date->diffInDays($this->start_date);
    }

    public function isEditableByStudent()
    {
        return in_array($this->status_code, [
            TrainingStatusLookup::STATUS_CONTINUING,
        ]);
    }

    public function isContinuing()
    {
        return $this->status_code == TrainingStatusLookup::STATUS_CONTINUING;
    }

    public function isCompleted()
    {
        return $this->status_code == TrainingStatusLookup::STATUS_COMPLETED;
    }

    public function isWithdrawn()
    {
        return $this->status_code == TrainingStatusLookup::STATUS_WITHDRAWN;
    }

    public function completedOtjSeconds()
    {
        $acceptedOtjSeconds = $this->otj()
            ->accepted()
            ->sum(\DB::raw('TIME_TO_SEC(duration)'));

        return $acceptedOtjSeconds;
    }

    public function completedOtj($formatted = true)
    {
        $acceptedOtjSeconds = $this->completedOtjSeconds();
        return $acceptedOtjSeconds == 0 ? 0 : ($formatted ? AppHelper::convertSecondsToHoursMinutes($acceptedOtjSeconds) : $acceptedOtjSeconds);
    }

    public function signedOffPercentage()
    {
        $signedOff = 0;
        $total = 0;

        foreach ($this->portfolios as $portfolio) {
            $signedOff += $portfolio->signedOffPCs();
            $total += $portfolio->totalPCs();
        }

        if ($total === 0) {
            return 0;
        }

        return round(($signedOff / $total) * 100);
    }

    public function overallRagRating()
    {
        $targetProgress = $this->target_progress;
        $actualProgress = $this->signedOffPercentage();
        if ($targetProgress == 0) {
            return '';
        }
        $percentBehind = $targetProgress - $actualProgress;

        if ($actualProgress >= $targetProgress) {
            return 'Green'; // On or above target
        } elseif ($percentBehind <= 10) {
            return 'Amber'; // Within 10% behind target
        } else {
            return 'Red';   // More than 10% behind target
        }
    }

    public function getActualWeeksOnProgrammeAttribute()
    {
        $totalWeeksOnProgramme = $this->planned_end_date->diffInWeeks($this->start_date);
        $annualLeaveFOrTotalWeeksOnProgramme = ($totalWeeksOnProgramme / 52.1429) * AppHelper::YEARLY_ANNUAL_LEAVE;
        $actualWeeksOnProgramme = $totalWeeksOnProgramme - $annualLeaveFOrTotalWeeksOnProgramme;
        return round($actualWeeksOnProgramme);
    }
    /*
    public static function boot()
    {
        parent::boot();
        self::deleting(function ($training_record) {

	    $training_record->media()->each(function ($media) {
                $media->delete();
            });

	    $training_record->evidences()->each(function ($evidence) {
                $evidence->delete();
            });

            $training_record->portfolios()->each(function ($portfolio) {
                $portfolio->delete();
            });

            $training_record->training_plans()->each(function ($trainingPlan) {
                $trainingPlan->delete();
            });

            $training_record->sessions()->each(function ($session) {
                $session->delete();
            });

            $training_record->otj()->each(function ($otj) {
                $otj->delete();
            });

            $training_record->reviews()->each(function ($review) {
                $review->delete();
            });

	    $training_record->crmNotes()->each(function ($review) {
                $review->delete();
            });

        });
    }
*/
    public function updateWithoutEvents(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->update($options);
        });
    }

    public function getTargetProgressAttribute()
    {
        $programme = $this->programme;
        $leewayInDays = $programme->leeway * 7;

        // Calculate the elapsed time
        $startDate = Carbon::parse($this->start_date);
        $leewayStartDate = $startDate->addDays($leewayInDays);
        $currentDate = Carbon::now();
        $elapsedDays = $currentDate->greaterThan($leewayStartDate)
            ? $leewayStartDate->diffInDays($currentDate)
            : 0;

        // Calculate the total duration
        $plannedEndDate = Carbon::parse($this->planned_end_date);
        $totalDuration = $startDate->diffInDays($plannedEndDate);

        // Avoid division by zero
        if ($totalDuration <= 0) {
            return 0;
        }

        // Calculate the target progress percentage
        $progress = ($elapsedDays / $totalDuration) * 100;

        // Ensure progress does not exceed 100%
        return round(min($progress, 100));
    }

    public function getOtjTargetProgressAttribute()
    {
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->planned_end_date);
        $currentDate = Carbon::now();

        if ($this->otj_hours == 0) {
            return ['expected_progress' => 0, 'expected_otj_hours' => 0];
        } elseif ($currentDate < $startDate) {
            return ['expected_progress' => 0, 'expected_otj_hours' => 0];
        } elseif ($currentDate > $endDate) {
            return ['expected_progress' => 100, 'expected_otj_hours' => $this->otj_hours];
        }

        $totalDays = $startDate->diffInDays($endDate);
        $elapsedDays = $startDate->diffInDays($currentDate);

        // Avoid division by zero
        if ($totalDays == 0) {
            return ['expected_progress' => 100, 'expected_otj_hours' => $this->otj_hours];
        }

        // Daily OTJ hours allocation
        $dailyOTJHours = $this->otj_hours / $totalDays;

        $expectedOTJHours = $dailyOTJHours * $elapsedDays;

        // Calculate expected progress as a percentage
        $expectedProgress = ($expectedOTJHours / $this->otj_hours) * 100;

        return [
            'expected_progress' => round($expectedProgress),
            'expected_otj_hours' => round($expectedOTJHours)
        ];
    }

    public function transformAudit(array $data): array
    {
        // if (\Illuminate\Support\Arr::has($data, 'new_values.verifier')) {
        //     $data['old_values']['verifier'] = User::find($this->getOriginal('verifier'))->full_name;
        //     $data['new_values']['verifier'] = User::find($this->getAttribute('verifier'))->full_name;
        // }
        // if (\Illuminate\Support\Arr::has($data, 'new_values.start_date')) {
        //     $data['old_values']['start_date'] = \Carbon\Carbon::parse($this->getOriginal('start_date'))->format('d/m/Y');
        //     $data['new_values']['start_date'] = $this->getAttribute('start_date');
        // }
        // if (\Illuminate\Support\Arr::has($data, 'new_values.planned_end_date')) {
        //     $data['old_values']['planned_end_date'] = \Carbon\Carbon::parse($this->getOriginal('planned_end_date'))->format('d/m/Y');
        //     $data['new_values']['planned_end_date'] = $this->getAttribute('planned_end_date');
        // }
        // if (\Illuminate\Support\Arr::has($data, 'new_values.employer_location')) {
        //     $data['old_values']['employer_location'] = \App\Models\Organisations\Location::find($this->getOriginal('employer_location'))->organisation->legal_name;
        //     $data['new_values']['employer_location'] = \App\Models\Organisations\Location::find($this->getAttribute('employer_location'))->organisation->legal_name;
        // }
        return $data;
    }
}
