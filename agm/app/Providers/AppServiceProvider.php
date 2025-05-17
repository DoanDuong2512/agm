<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Session;
use App\Observers\CustomerObserver;
use App\Observers\SessionObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        Customer::observe(CustomerObserver::class);
        Session::observe(SessionObserver::class);
        Paginator::defaultView('vendor.pagination.custom');
    }
}
