<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Cloud Messaging
    |--------------------------------------------------------------------------
    |
    | FCM is deliberately disabled until it is configured in the environment.
    | No Firebase credentials belong in the repository.
    |
    */
    'enabled' => (bool) env('FCM_ENABLED', false),
    'project_id' => env('FCM_PROJECT_ID'),
    'service_account_path' => env(
        'FCM_SERVICE_ACCOUNT_PATH',
        'storage/app/firebase/service-account.json'
    ),
    'ca_bundle_path' => env('FCM_CA_BUNDLE_PATH'),
    'endpoint' => env('FCM_ENDPOINT'),
    'timeout' => (int) env('FCM_TIMEOUT', 5),
];
