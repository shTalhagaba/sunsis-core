<?php

namespace App\Providers;

use App\Listeners\PasswordResetListener;
use App\Models\Training\PCEvidenceMapping;
use App\Models\Training\PortfolioPC;
use App\Models\Training\TrainingRecord;
use App\Observers\PcEvidenceMappingObserver;
use App\Observers\PortfolioPcObserver;
use App\Observers\TrainingRecordObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \Illuminate\Auth\Events\Failed::class => [
            \App\Listeners\RecordFailedLogin::class,
        ],
        Login::class => [
            \App\Listeners\LogSuccessfulLogin::class,
        ],
        Logout::class => [
            \App\Listeners\LogSuccessfulLogout::class,
        ],
        \App\Events\NewUserHasBeenCreatedEvent::class => [
            \App\Listeners\NewUserLoginDetailsListener::class,
        ],
        \App\Events\NewUserNotificationToSupportEvent::class => [
            \App\Listeners\NewUserNotificationToSupportListener::class,
        ],
        PasswordReset::class => [
            PasswordResetListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //TrainingRecordEvidence::observe(TrainingRecordEvidenceObserver::class);
        PCEvidenceMapping::observe(PcEvidenceMappingObserver::class);
	TrainingRecord::observe(TrainingRecordObserver::class);
        PortfolioPC::observe(PortfolioPcObserver::class);
    }
}
