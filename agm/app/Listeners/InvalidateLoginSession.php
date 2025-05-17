<?php

namespace App\Listeners;

use App\Events\CustomerLoggedOut;
use App\Services\TokenService;
use Exception;
use Illuminate\Support\Facades\Log;

class InvalidateLoginSession
{
    /**
     * @param CustomerLoggedOut $event
     */
    public function handle(CustomerLoggedOut $event)
    {
        try {
            $payload = TokenService::decode($event->token);

            $session = $event->customer->sessions()
                ->where('token_id', $payload->get('jti'))
                ->first();
            optional($session)->invalidate();
        } catch (Exception $exception) {
            Log::error('Invalidate login session error: ' . $exception->getMessage());
        }
    }
}
