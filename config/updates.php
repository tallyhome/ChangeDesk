<?php

return [
    /*
    | Version locale (mise à jour après une MAJ réussie).
    */
    'number' => '2.4.0',

    'github_api' => \App\Support\GithubUpdateAuth::API,

    'github_repo' => \App\Support\GithubUpdateAuth::REPO,

    'github_token' => \App\Support\GithubUpdateAuth::token(),

    /*
    | Chemins jamais écrasés lors d'une mise à jour.
    */
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
