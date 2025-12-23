<?php


return [

    'api_key' => env('GEMINI_API_KEY'),

    'endpoint' => env(
        'GEMINI_ENDPOINT',
        'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-image:generateContent'
    ),

    /*
    |--------------------------------------------------------------------------
    | Storage Configuration
    |--------------------------------------------------------------------------
    */

    'disks' => [
        'input'  => env('GEMINI_INPUT_DISK', 'local'),   // private
        'output' => env('GEMINI_OUTPUT_DISK', 'public'), // public
    ],

    'paths' => [

        // Input uploads
        'front' => 'gemini/front',
        'back' => 'gemini/back',
        'reference' => 'gemini/ref',

        // Output (generated banners)
        'output' => 'gemini/output',
    ],

    'cleanup' => [
        'enabled' => true,
    ]
];
