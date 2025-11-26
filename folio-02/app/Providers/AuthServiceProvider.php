<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Student' => 'App\Policies\StudentPolicy',
        'App\Models\Programmes\Programme' => 'App\Policies\ProgrammePolicy',
        'App\Models\Training\TrainingRecord' => 'App\Policies\TrainingRecordPolicy',
        'App\Models\Training\Otj' => 'App\Policies\OtjPolicy',
        'App\Models\Training\TrainingRecordEvidence' => 'App\Policies\TrainingRecordEvidencePolicy',
        'App\Models\Training\TrainingReview' => 'App\Policies\TrainingRecordReviewPolicy',
        'App\Models\Organisations\Organisation' => 'App\Policies\OrganisationPolicy',
        'App\Models\Qualifications\Qualification' => 'App\Policies\QualificationPolicy',
        'App\Models\Todo\TodoTask' => 'App\Policies\TodoTaskPolicy',
        'App\Models\IQA\IqaSamplePlan' => 'App\Policies\IqaSamplePlanPolicy',
        'App\Models\UserEvents\UserEvent' => 'App\Policies\UserEventPolicy',
        'App\Models\Training\Portfolio' => 'App\Policies\TrainingPortfolioPolicy',
        'App\Models\LearningResources\LearningResource' => 'App\Policies\LearningResourcePolicy',
        'App\Models\FSAssessment\TestSession' => 'App\Policies\TestSessionPolicy',
        'App\Models\AssessorRiskAssessment\AssessorRiskAssessment' => 'App\Policies\AssessorRiskAssessmentPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Schema::defaultStringLength(191);
    }
}
