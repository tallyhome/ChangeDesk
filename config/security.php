<?php

return [

    /*
    | Proxies qui peuvent définir X-Forwarded-For. Vide = aucun.
    | Liste d'adresses séparées par des virgules, ou * uniquement si le
    | reverse proxy est le seul chemin vers PHP.
    */
    'trusted_proxies' => env('TRUSTED_PROXIES'),

    /*
    | Secret partagé avec Caddy (on_demand_tls). En production, vide = refus.
    */
    'ssl_ask_token' => env('SSL_ASK_TOKEN', ''),

    /*
    | Durée de conservation des adresses IP de visite, en jours. 0 = illimité.
    */
    'visit_retention_days' => (int) env('VISIT_RETENTION_DAYS', 90),

    /*
    | Inscription publique. false ferme /register.
    */
    'registration_enabled' => filter_var(env('REGISTRATION_ENABLED', true), FILTER_VALIDATE_BOOL),

];
