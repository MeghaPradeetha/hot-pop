<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    | Supported: "pusher", "ably", "redis", "log", "null"
    |
    */

    'default' => env('BROADCAST_DRIVER', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over websockets. Samples of
    | each available type of connection are provided inside this array.
    |
    */

    'connections' => [

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'host' => env('PUSHER_HOST') ?: 'api-'.env('PUSHER_APP_CLUSTER', 'mt1').'.pusher.com',
                'port' => env('PUSHER_PORT', 443),
                'scheme' => env('PUSHER_SCHEME', 'https'),
                'encrypted' => true,
                'useTLS' => env('PUSHER_SCHEME', 'https') === 'https',
            ],
            'client_options' => [
                // Guzzle client options: https://docs.guzzlephp.org/en/stable/request-options.html
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],
        'apn' => [
            'production' => env('APN_PRODUCTION', false),
            'environment' => env('APN_ENVIRONMENT', 'sandbox'), // Change to 'production' if needed
            'use_sandbox' => env('APN_USE_SANDBOX', true),  // true for development, false for production
            'key_id' => env('APN_KEY_ID'),
            'team_id' => env('APN_TEAM_ID'),
            'app_identifier' => env('APN_APP_IDENTIFIER'),
            'private_key_path' => env('APN_KEY_PATH'),
            'certificate_path' => env('APN_CERTIFICATE_PATH'),
            'certificate_secret' => env('APN_CERTIFICATE_PASS'),
            'topic' => env('APN_TOPIC'),
            'app_bundle_id' => env('APN_APP_IDENTIFIER'),
            'push_type' => env('APN_PUSH_TYPE', 'voip'),
        ],

    ],

];
