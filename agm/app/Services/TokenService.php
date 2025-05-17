<?php

namespace App\Services;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Manager;
use Tymon\JWTAuth\Token;
use Tymon\JWTAuth\Payload;

class TokenService
{
    /**
     * @param $token
     * @return bool
     * @throws JWTException
     */
    public static function revoke($token): bool
    {
        $manager = self::getManager();

        return $manager->invalidate(new Token($token));
    }

    /**
     * @param $token
     * @return Payload
     * @throws \Tymon\JWTAuth\Exceptions\TokenBlacklistedException
     */
    public static function decode($token): Payload
    {
        $manager = self::getManager();

        return $manager->decode(new Token($token), false);
    }

    /**
     * @return Manager
     */
    public static function getManager(): Manager
    {
        return app('tymon.jwt.manager');
    }
}
