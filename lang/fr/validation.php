<?php

return [
    'confirmed' => 'La confirmation ne correspond pas.',
    'required' => 'Le champ :attribute est obligatoire.',
    'email' => 'Le champ :attribute doit être une adresse e-mail valide.',
    'unique' => 'Cette valeur est déjà utilisée.',
    'min' => [
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],
    'max' => [
        'string' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
    ],
    'exists' => 'La valeur sélectionnée pour :attribute est invalide.',
    'in' => 'La valeur sélectionnée pour :attribute est invalide.',
    'password' => [
        'letters' => 'Le mot de passe doit contenir au moins une lettre.',
        'mixed' => 'Le mot de passe doit contenir des majuscules et des minuscules.',
        'numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
        'symbols' => 'Le mot de passe doit contenir au moins un symbole.',
        'uncompromised' => 'Ce mot de passe est trop courant. Choisissez-en un autre.',
    ],
    'attributes' => [
        'name' => 'nom',
        'email' => 'e-mail',
        'password' => 'mot de passe',
        'password_confirmation' => 'confirmation',
        'role' => 'rôle',
        'tenant_id' => 'tenant',
        'plan_id' => 'plan',
        'preferred_plan_id' => 'plan',
    ],
];
