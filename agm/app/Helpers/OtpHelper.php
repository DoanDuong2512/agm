<?php

namespace App\Helpers;

class OtpHelper
{
    public static function getKeyAttemptsFailed(string $vnID): string
    {
        return "otp_attempts_failed_$vnID";
    }

    public static function getKeyLocked(string $vnID): string
    {
        return "account_locked_$vnID";
    }
}
