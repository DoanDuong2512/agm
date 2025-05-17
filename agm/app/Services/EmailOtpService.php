<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Services\Interfaces\OtpSenderInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailOtpService implements OtpSenderInterface
{
    public function send(string $receiver_address, string $otp, int $minutes_expired): bool
    {
        try {
            Mail::to($receiver_address)->send(new OtpMail($otp, $minutes_expired));
            return true;
        } catch (\Exception $e) {
            Log::error('Send otp through email error:' . $e->getMessage());
            return false;
        }
    }
}
