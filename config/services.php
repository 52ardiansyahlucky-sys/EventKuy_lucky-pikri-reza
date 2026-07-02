<?php

return [

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'openweather' => [
        // Pastikan env key sesuai .env: OPENWEATHER_KEY (atau fallback OPENWEATHER_API_KEY)
        'key' => env('OPENWEATHER_KEY', env('OPENWEATHER_API_KEY')),
        'base_url' => env(
            'OPENWEATHER_BASE_URL',
            'https://api.openweathermap.org/data/2.5'
        ),
    ],

    'google_geocoding' => [
        'key' => env('GOOGLE_GEOCODING_API_KEY'),
    ],

];

