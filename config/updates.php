<?php

return [
    'number' => '2.8.11',

    'github_api' => \App\Support\GithubUpdateAuth::API,

    'github_repo' => \App\Support\GithubUpdateAuth::REPO,

    'github_token' => env('GITHUB_UPDATE_TOKEN', ''),

    'preserve' => [
        '.env',
        'storage/app',
        'storage/framework',
        'storage/logs',
        'bootstrap/cache',
        'database/database.sqlite',
        'app/Support/GithubUpdateAuth.php',
    ],
];
