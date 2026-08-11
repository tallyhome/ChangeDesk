<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Central domain (used to build tenant URLs)
    |--------------------------------------------------------------------------
    */
    'central_domain' => env('CENTRAL_DOMAIN', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST) ?: 'localhost'),

    /*
    |--------------------------------------------------------------------------
    | Hosts treated as the central SaaS site (no public tenant data)
    |--------------------------------------------------------------------------
    */
    'central_domains' => array_values(array_filter(array_map(
        'trim',
        explode(',', env('CENTRAL_DOMAINS', ''))
    ))) ?: array_values(array_unique(array_filter([
        parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST) ?: 'localhost',
        'localhost',
        '127.0.0.1',
        'www.'.(parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST) ?: 'localhost'),
    ]))),

    /*
    |--------------------------------------------------------------------------
    | CNAME target clients should point their custom domain to
    |--------------------------------------------------------------------------
    */
    'cname_target' => env('TENANCY_CNAME_TARGET', env('CENTRAL_DOMAIN', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST) ?: 'localhost')),

];
