<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Les URIs exemptées de la vérification CSRF.
     * Le webhook FedaPay est appelé par un serveur externe — pas de token CSRF.
     */
    protected $except = [
        'client/paiements/webhook',
        'proprietaire/loyer/webhook',
    ];
}
