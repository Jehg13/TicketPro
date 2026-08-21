<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return redirect()->route('login');
        }

        if (in_array('admin', $roles)) {

            if (
                in_array($usuario->role, [
                    'Gerente Ti',
                    'Soporte Tecnico'
                ]) &&
                $usuario->priv_admin === 'Y'
            ) {
                return $next($request);
            }

            return redirect()->route('dashboard');
        }

        if (!in_array($usuario->role, $roles)) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}