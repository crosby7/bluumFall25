<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [],

    'allowed_origins_patterns' => [
        '/localhost:\d+/',    // This covers 8081, 3000, 8082...
        '/127\.0\.0\.1:\d+/', // This covers the IP version
        '/10\.\d+\.\d+\.\d+:\d+/', // This covers local network IPs
        '/.*\.sharedwithexpose\.com/', // Expose public URLs
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
