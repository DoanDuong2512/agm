<?php
return [
    'expire_time' => env('OTP_EXPIRE_TIME', 5),
    'time_between_requests' => env('OTP_TIME_BETWEEN_REQUESTS', 60),
    'temp_token_expire_time' => env('TEMP_TOKEN_EXPIRE_TIME', 10080),
    'attempts_failed_time' => env('OTP_FAILED_TIME', 5),
    'account_locked_time' => env('ACCOUNT_LOCKED_TIME', 5),
    'attempts_failed_duration' => env('OTP_FAILED_DURATION', 5),
    'temp_token_first_login_expire_time' => env('TEMP_TOKEN_FIRST_LOGIN_EXPIRE_TIME', 1800),
];
