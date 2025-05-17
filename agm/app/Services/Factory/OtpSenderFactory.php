<?php

namespace App\Services\Factory;

use App\Services\EmailOtpService;
use App\Services\Interfaces\OtpSenderInterface;

class OtpSenderFactory
{
    public static function make(string $type): OtpSenderInterface
    {
        return match ($type) {
            'email' => app(EmailOtpService::class),
            default => throw new \InvalidArgumentException("Invalid OTP sender type: $type"),
        };
    }
}
