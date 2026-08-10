<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {

            if (Auth::guard($guard)->check()) {

                $usuario = Auth::guard($guard)->user();

                return match ($usuario->rol) {

                    'usuario' => redirect()->route('dashboard'),

                    'tecnologias' => redirect()->route('tecnologias'),

                    'admin' => redirect()->route('admin'),

                    default => abort(403, 'Rol de usuario no válido.'),
                };
            }
        }

        return $next($request);
    }
}