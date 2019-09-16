<?php

return [
    'credentials' => [
        'key' => env('AWS_ID'),
        'secret' => env('AWS_KEY'),
    ],
    'region' => env('AWS_REGION', 'us-east-1'),
    'version' => 'latest',

    // You can override settings for specific services
    'Ses' => [
        'region' => env('AWS_REGION', 'us-east-1'),
    ],
];