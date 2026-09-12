<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'citizen'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'staff'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'citizens',
        ],

        'citizen' => [
            'driver' => 'session',
            'provider' => 'citizens',
        ],

        'staff' => [
            'driver' => 'session',
            'provider' => 'staff',
        ],
    ],

    'providers' => [
        'citizens' => [
            'driver' => 'eloquent',
            'model' => App\Models\Citizen::class,
        ],

        'staff' => [
            'driver' => 'eloquent',
            'model' => App\Models\Staff::class,
        ],
    ],

    'passwords' => [
        'staff' => [
            'provider' => 'staff',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
