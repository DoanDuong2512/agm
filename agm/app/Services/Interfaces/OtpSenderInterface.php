<?php
namespace App\Services\Interfaces;

interface OtpSenderInterface
{
    public function send(string $receiver_address, string $otp, int $minutes_expired): bool;
}
