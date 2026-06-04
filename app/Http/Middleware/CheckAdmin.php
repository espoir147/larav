<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->type !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        if (auth()->user()->statut !== 'actif') {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Compte inactif.']);
        }

        return $next($request);
    }
}
