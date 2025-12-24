<?php

use App\Models\User;

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

    'mailgun'   => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme'   => 'https',
    ],

    'postmark'  => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses'       => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'fcm'       => [
        'key'          => env('FCM_SERVER_KEY'), // Your FCM Server Key
        'sender_id'    => env('FCM_SENDER_ID'), // Your FCM Sender ID
        'notification' => [
            'sound' => 'default', // Default sound for notifications
        ],
    ],

    'stripe'    => [
        'model'  => User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'agora' => [
        'app_id' => env('AGORA_APP_ID'),
        'app_certificate' => env('AGORA_APP_CERTIFICATE'),
    ],

    'twilio'    => [
        'account_sid' => env("TWILIO_ACCOUNT_SID"),
        'api_key'     => env("TWILIO_API_KEY"),
        'api_secret'  => env("TWILIO_API_SECRET"),
        'app_sid'     => env("TWILIO_SID")
    ],

    'facebook'  => [
        'client_id'     => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect'      => env('FACEBOOK_REDIRECT_URI', '/auth/facebook/callback')
    ],

    'google'    => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URI', '/auth/google/callback')
    ],

    'tiktok'    => [
        'client_id'     => env('TIKTOK_CLIENT_ID'),
        'client_secret' => env('TIKTOK_CLIENT_SECRET'),
        'redirect'      => env('TIKTOK_REDIRECT_URI', '/auth/tiktok/callback')
    ],

    'instagram' => [
        'client_id'     => env('INSTAGRAM_CLIENT_ID'),
        'client_secret' => env('INSTAGRAM_CLIENT_SECRET'),
        'redirect'      => env('INSTAGRAM_REDIRECT_URI', '/auth/instagram/callback')
    ],

];