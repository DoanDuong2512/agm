<?php

namespace App\Services;

use App\Models\Session;
use Illuminate\Support\Facades\Log;

class SessionService
{
    public static function invalidate(Session $session)
    {
        try {
            $session->invalidate();
        } catch (\Exception $exception) {
            Log::error('Session service invalidate error: ' . $exception->getMessage());
        }
    }

    public static function invalidateAll($sessionable)
    {
        try {
            $sessionable->sessions()
                ->invalidate(false)
                ->update([
                    'invalidated_at' => now()
                ]);
        } catch (\Exception $exception) {
            Log::error('Session service invalidate all error: ' . $exception->getMessage());
        }
    }
}
