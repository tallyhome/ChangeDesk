<?php

return [
    'number' => '2.6.0',

    'github_api' => \App\Support\GithubUpdateAuth::API,

    'github_repo' => \App\Support\GithubUpdateAuth::REPO,

    'github_token' => \App\Support\GithubUpdateAuth::token(),

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
