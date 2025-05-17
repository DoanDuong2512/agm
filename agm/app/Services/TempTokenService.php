<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TempTokenService
{
    public static function create($identifier, $ttl)
    {
        try {
            $tempToken = bin2hex(random_bytes(16));
            Cache::put("temp_token_$identifier", $tempToken, $ttl);
            return $tempToken;
        } catch (\Exception $exception) {
            Log::error('TempTokenService create error: ' . $exception->getMessage());
            return '';
        }
    }

    public static function delete($identifier)
    {
        try {
            Cache::forget("temp_token_$identifier");
            return true;
        } catch (\Exception $exception) {
            Log::error('TempTokenService remove error: ' . $exception->getMessage());
            return false;
        }
    }
}

