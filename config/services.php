<?php

return [

    'khalti' => [
        'public_key' => env('KHALTI_PUBLIC_KEY'),
        'secret_key' => env('KHALTI_SECRET_KEY'),
        'base_url' => env('KHALTI_BASE_URL', 'https://a.khalti.com/api/v2/'),
    ],

    'sms' => [
        'provider' => env('SMS_PROVIDER', 'log'), // sparrow, aakash, twilio, generic, log
        'from' => env('SMS_FROM', 'WardSewa'),
        'debug' => env('SMS_DEBUG', false),

        // Sparrow SMS (Nepal: api.sparrowsms.com)
        'sparrow' => [
            'token' => env('SPARROW_SMS_TOKEN', env('SMS_API_KEY')),
            'from' => env('SPARROW_SMS_FROM', env('SMS_FROM', 'WardSewa')),
            'url' => env('SPARROW_SMS_URL', 'http://api.sparrowsms.com/v2/sms/'),
        ],

        // Aakash SMS (Nepal: sms.aakashsms.com)
        'aakash' => [
            'token' => env('AAKASH_SMS_TOKEN', env('SMS_API_KEY')),
            'url' => env('AAKASH_SMS_URL', 'https://sms.aakashsms.com/sms/v3/send'),
        ],

        // Twilio (International SMS)
        'twilio' => [
            'sid' => env('TWILIO_SID'),
            'token' => env('TWILIO_AUTH_TOKEN'),
            'from' => env('TWILIO_FROM_NUMBER'),
        ],

        // Generic HTTP SMS Gateway / Custom Webhook
        'generic' => [
            'url' => env('SMS_GENERIC_URL'),
            'method' => env('SMS_GENERIC_METHOD', 'POST'),
            'token' => env('SMS_GENERIC_TOKEN'),
            'to_field' => env('SMS_GENERIC_TO_FIELD', 'to'),
            'message_field' => env('SMS_GENERIC_MESSAGE_FIELD', 'text'),
        ],
    ],

];
