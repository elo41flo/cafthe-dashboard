<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Auth::user() renvoie l'employé connecté (instance du modèle Employe).
        // Si personne n'est connecté, ou si son rôle n'est pas "admin", on bloque l'accès.
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        // Sinon, on laisse la requête continuer normalement vers la route demandée
        return $next($request);
    }
}