<?php
namespace App\Services;
use App\Models\Otp;
use App\Services\Factory\OtpSenderFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OtpService
{
    public string $senderType;
    public int $minutes_expire;

    public function __construct($senderType)
    {
        $this->senderType = $senderType;
        $this->minutes_expire = (int)config('otp.expire_time', 5);
    }

    public function generateOtp(): string
    {
        return strval(rand(100000, 999999));
    }

    public function sendOtp(string $receiver_address, string $otp = null): bool
    {
        try {
            if (empty($otp)) {
                $otp = $this->generateOtp();
            }
            $otpSender = OtpSenderFactory::make($this->senderType);
            $otpSender->send($receiver_address, $otp, $this->minutes_expire);
            $this->deletePreviousOtp($receiver_address);
            $this->saveOtpToDatabase($receiver_address, $otp);
            return true;
        } catch (\Exception $e) {
            Log::error('Send otp error: ' . $e->getMessage());
            return false;
        }
    }

    public function saveOtpToDatabase(string $receiver_address, string $digit_code): bool
    {
        try {
            $ttl = (int) config('otp.expire_time', 5) * 60;
            $expire_at = Carbon::now()->addSeconds($ttl);
            Otp::create([
                'receiver_address' => $receiver_address,
                'digit_code' => $digit_code,
                'sent_through' => $this->getSenderType(),
                'expire_at' => $expire_at,
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Save otp to database error: ' . $e->getMessage());
            return false;
        }
    }

    public function saveOtpToCache(string $receiver_address, string $digit_code): bool
    {
        try {
            $ttl = (int) config('otp.expire_time', 5) * 60;
            $key = $receiver_address . '_' . 'otp';
            Cache::put($key, $digit_code, $ttl);
            return true;
        } catch (\Exception $e) {
            Log::error('Save otp to cache error: ' . $e->getMessage());
            return false;
        }
    }

    private function getSenderType(): string
    {
        return $this->senderType;
    }

    public function deletePreviousOtp($receiver_address): bool
    {
        try {
            Otp::where([
                'receiver_address' => $receiver_address,
                'sent_through' => $this->getSenderType(),
            ])->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Delete previous otp error: ' . $e->getMessage());
            return false;
        }
    }
}
