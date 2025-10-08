<?php

return [
    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'currency' => env('STRIPE_CURRENCY', 'usd'),

    'routes' => [
        'prefix' => env('STRIPE_ROUTES_PREFIX', 'stripe'),
        'middleware' => ['web'],
    ],

    'views' => [
        'layout' => 'easy-stripe::layouts.app',
        'theme' => 'bootstrap', // bootstrap, tailwind
    ],

    'features' => [
        'invoices' => true,
        'webhooks' => true,
        'payment_intents' => true,
        'checkout_sessions' => true,
    ],

    'branding' => [
        'primary_color' => '#667eea',
        'secondary_color' => '#764ba2',
        'app_name' => env('APP_NAME', 'Laravel App'),
    ],
];
