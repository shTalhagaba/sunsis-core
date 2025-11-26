<?php

namespace App\Providers;

use App\Services\ConfigurationService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('app-config', function ($app) {
            return new ConfigurationService();
        });
    
        // Load configuration values when the application starts
        $configurationService = app('app-config');
        $configurationService->loadConfiguration();

        // crm notes noteable
        Route::bind('noteable', function ($value, $route) {
            $noteableType = $route->parameter('noteable_type');
            $modelClass = null;
            switch($noteableType) {
                case 'trainings':
                    $modelClass = \App\Models\Training\TrainingRecord::class;
                    break;
                case 'students':
                    $modelClass = \App\Models\Student::class;
                    break;
                default:
                    break;
            }
    
            if ($modelClass && class_exists($modelClass)) {
                return $modelClass::findOrFail($value);
            }
    
            abort(404, 'Invalid noteable type');
        });

        config(['medialibrary.max_file_size' => 314572801]);
    }
}
