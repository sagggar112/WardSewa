<?php

return [

    'khalti' => [
        'public_key' => env('KHALTI_PUBLIC_KEY'),
        'secret_key' => env('KHALTI_SECRET_KEY'),
        'base_url' => env('KHALTI_BASE_URL', 'https://a.khalti.com/api/v2/'),
    ],

    'sms' => [
        'provider' => env('SMS_PROVIDER', 'log'),
        'api_key' => env('SMS_API_KEY'),
    ],

];
