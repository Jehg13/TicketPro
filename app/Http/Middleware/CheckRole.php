<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        $usuario = Auth::user();

        if (!$usuario || $usuario->rol !== $role) {
            abort(403, 'No tienes los permisos requeridos para acceder a esta sección.');
        }

        return $next($request);
    }
}