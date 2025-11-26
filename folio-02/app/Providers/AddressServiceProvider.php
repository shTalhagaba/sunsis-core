<?php

namespace App\Providers;

use App\Models\Address;
use App\Observers\AddressObserver;
use Illuminate\Support\ServiceProvider;

class AddressServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Address::observe(AddressObserver::class);        
    }
}
