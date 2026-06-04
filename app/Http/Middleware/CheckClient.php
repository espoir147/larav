<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckClient
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!in_array(auth()->user()->type, ['client', 'admin'])) {
            abort(403, 'Accès réservé aux clients.');
        }

        if (auth()->user()->statut !== 'actif') {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Compte inactif.']);
        }

        return $next($request);
    }
}
