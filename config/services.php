<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

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

    'google' => [
        'maps_key' => env('GOOGLE_MAPS_API_KEY'),
        'maps_country' => env('GOOGLE_MAPS_COUNTRY', 'ID'),
        'maps_language' => env('GOOGLE_MAPS_LANGUAGE', 'id'),
        'maps_region' => env('GOOGLE_MAPS_REGION', 'ID'),
    ],

    'nominatim' => [
        'base_url' => env('OSM_NOMINATIM_URL', 'https://nominatim.openstreetmap.org'),
        'email' => env('OSM_NOMINATIM_EMAIL'),
        'language' => env('OSM_NOMINATIM_LANGUAGE', 'id'),
    ],

];
