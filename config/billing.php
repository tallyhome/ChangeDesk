<?php

return [
    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'key' => env('STRIPE_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'prices' => [
            'pro' => env('STRIPE_PRICE_PRO'),
            'business' => env('STRIPE_PRICE_BUSINESS'),
        ],
    ],
    'paypal' => [
        'mode' => env('PAYPAL_MODE', 'sandbox'),
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'client_secret' => env('PAYPAL_CLIENT_SECRET'),
        'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
        'plans' => [
            'pro' => env('PAYPAL_PLAN_PRO'),
            'business' => env('PAYPAL_PLAN_BUSINESS'),
        ],
    ],
    'currency' => env('BILLING_CURRENCY', 'eur'),
];
