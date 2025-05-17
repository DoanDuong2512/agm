<?php

namespace App\Listeners;

use App\Events\CustomerLoggedIn;
use App\Services\TokenService;

class StoreCustomerLoginSession
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {}

    /**
     * Handle the event.
     */
    public function handle(CustomerLoggedIn $event): void
    {
        $payload = TokenService::decode($event->token);

        $event->customer->sessions()->create([
            'token_id' => $payload->get('jti'),
            'agent' => request()->userAgent(),
            'token' => $event->token,
            'ip_address' => request()->ip(),
        ]);
    }
}
