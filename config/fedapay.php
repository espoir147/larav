<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Clé publique FedaPay
    |--------------------------------------------------------------------------
    | Utilisée côté front (JS) pour initialiser le widget FedaPay.
    | Ne jamais mettre la clé secrète ici.
    */
    'public_key' => env('FEDAPAY_PUBLIC_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Clé secrète FedaPay
    |--------------------------------------------------------------------------
    | Utilisée côté serveur uniquement (appels API depuis le backend).
    */
    'secret_key' => env('FEDAPAY_SECRET_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Environnement
    |--------------------------------------------------------------------------
    | 'sandbox' pour les tests, 'live' pour la production.
    | Doit être cohérent avec les clés utilisées.
    */
    'environment' => env('FEDAPAY_ENVIRONMENT', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | URL de callback (webhook)
    |--------------------------------------------------------------------------
    | FedaPay appellera cette URL pour notifier le statut du paiement.
    */
    'webhook_secret' => env('FEDAPAY_WEBHOOK_SECRET', ''),

];
