<?php

return [

    'default' => env('APP_LOCALE', 'fr'),

    'supported' => [
        'fr' => ['name' => 'Français', 'native' => 'Français', 'flag' => '🇫🇷', 'dir' => 'ltr'],
        'en' => ['name' => 'English', 'native' => 'English', 'flag' => '🇬🇧', 'dir' => 'ltr'],
        'de' => ['name' => 'German', 'native' => 'Deutsch', 'flag' => '🇩🇪', 'dir' => 'ltr'],
        'es' => ['name' => 'Spanish', 'native' => 'Español', 'flag' => '🇪🇸', 'dir' => 'ltr'],
        'it' => ['name' => 'Italian', 'native' => 'Italiano', 'flag' => '🇮🇹', 'dir' => 'ltr'],
        'pl' => ['name' => 'Polish', 'native' => 'Polski', 'flag' => '🇵🇱', 'dir' => 'ltr'],
        'pt' => ['name' => 'Portuguese', 'native' => 'Português', 'flag' => '🇵🇹', 'dir' => 'ltr'],
        'nl' => ['name' => 'Dutch', 'native' => 'Nederlands', 'flag' => '🇳🇱', 'dir' => 'ltr'],
        'ru' => ['name' => 'Russian', 'native' => 'Русский', 'flag' => '🇷🇺', 'dir' => 'ltr'],
    ],

    'date' => [
        'fr' => 'd/m/Y',
        'en' => 'M j, Y',
        'de' => 'd.m.Y',
        'es' => 'd/m/Y',
        'it' => 'd/m/Y',
        'pl' => 'd.m.Y',
        'pt' => 'd/m/Y',
        'nl' => 'd-m-Y',
        'ru' => 'd.m.Y',
    ],
];
