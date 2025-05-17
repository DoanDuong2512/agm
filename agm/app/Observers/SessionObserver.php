<?php

namespace App\Observers;

use App\Models\Session;
use App\Services\TokenService;

class SessionObserver
{
    /**
     * @param Session $session
     * @return void
     */
    public function deleted(Session $session)
    {
        TokenService::revoke($session->token);
    }
}
