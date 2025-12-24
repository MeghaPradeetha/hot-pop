<?php

return [
    'key_path' => env('APN_KEY_PATH'), // Path to your .p12 or .pem certificate
    'key_secret' => env('APN_KEY_PASSWORD'), // Password for the certificate (if needed)
    'team_id' => env('APN_TEAM_ID'), // Apple Developer Team ID
    'key_id' => env('APN_KEY_ID'), // APNs Key ID from your Apple Developer Account
    'app_bundle_id' => env('APN_TOPIC'), // The Bundle ID of your app (should match your app's identifier)
    'topic' => env('APN_TOPIC'), // The Bundle ID of your app (should match your app's identifier)
    'production' => env('APP_ENV') === 'production', // True if using production APNs
];