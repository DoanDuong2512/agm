<?php

namespace App\Providers;

use App\Events\CustomerLoggedIn;
use App\Events\CustomerLoggedOut;
use App\Listeners\InvalidateLoginSession;
use App\Listeners\StoreCustomerLoginSession;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CustomerLoggedIn::class => [
            StoreCustomerLoginSession::class,
        ],
        CustomerLoggedOut::class => [
            InvalidateLoginSession::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
