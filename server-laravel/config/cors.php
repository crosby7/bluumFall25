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

   'allowed_origins' => [], // LEAVE EMPTY. Do not put env() here.

'allowed_origins_patterns' => [
    '/localhost:\d+/',    // This covers 8081, 3000, 8082...
    '/127\.0\.0\.1:\d+/', // This covers the IP version
],

'supports_credentials' => false, // <--- CHANGE THIS TO FALSE
];
