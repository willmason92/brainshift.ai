<?php

return [
    'name' => 'BrainShift',
    'manifest' => [
        'name' => env('APP_NAME', 'BrainShift'),
        'short_name' => 'BrainShift',
        'start_url' => 'https://brainshift.ai',
        'background_color' => '#e60000',
        'theme_color' => '#e60000',
        'display' => 'standalone',
        'orientation' => 'any',
        'status_bar' => 'black',
        'icons' => [
            '72x72' => [
                'path' => '/storage/icons/icon-72x72.png',
                'purpose' => 'any',
            ],
            '96x96' => [
                'path' => '/storage/icons/icon-96x96.png',
                'purpose' => 'any',
            ],
            '128x128' => [
                'path' => '/storage/icons/icon-128x128.png',
                'purpose' => 'any',
            ],
            '144x144' => [
                'path' => '/storage/icons/icon-144x144.png',
                'purpose' => 'any',
            ],
            '152x152' => [
                'path' => '/storage/icons/icon-152x152.png',
                'purpose' => 'any',
            ],
            '192x192' => [
                'path' => '/storage/icons/icon-192x192.png',
                'purpose' => 'any',
            ],
            '384x384' => [
                'path' => '/storage/icons/icon-384x384.png',
                'purpose' => 'any',
            ],
            '512x512' => [
                'path' => '/storage/icons/icon-512x512.png',
                'purpose' => 'any',
            ],
        ],
        'splash' => [
            '640x1136' => '/storage/icons/splash-640x1136.png',
            '750x1334' => '/storage/icons/splash-750x1334.png',
            '828x1792' => '/storage/icons/splash-828x1792.png',
            '1125x2436' => '/storage/icons/splash-1125x2436.png',
            '1242x2208' => '/storage/icons/splash-1242x2208.png',
            '1242x2688' => '/storage/icons/splash-1242x2688.png',
            '1536x2048' => '/storage/icons/splash-1536x2048.png',
            '1668x2224' => '/storage/icons/splash-1668x2224.png',
            '1668x2388' => '/storage/icons/splash-1668x2388.png',
            '2048x2732' => '/storage/icons/splash-2048x2732.png',
        ],
        'custom' => [],
    ],
];
