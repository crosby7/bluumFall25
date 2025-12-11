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
        '/192\.168\.\d+\.\d+:\d+/', // Common home/office networks
        '/10\.193\.\d+\.\d+:\d+/', // Another common private network range
        '/172\.(1[6-9]|2[0-9]|3[0-1])\.\d+\.\d+:\d+/', // Private network range 172.16.0.0 - 172.31.255.255
        '/.*\.sharedwithexpose\.com/', // Expose public URLs
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
