<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Redis;

class RedisHelper
{
    public static function getTTL(string $key)
    {
        return Redis::connection('cache')->ttl(config('cache.prefix') . ':' . $key);
    }
}
