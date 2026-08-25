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

        if (in_array('admin', $roles, true)) {
            if (
                in_array($usuario->role, [
                    'Gerente Ti',
                    'Soporte Tecnico'
                ], true) &&
                $usuario->priv_admin === 'Y'
            ) {
                return $next($request);
            }

            return redirect()->route('dashboard');
        }

        if (in_array('no-admin', $roles, true)) {
            $esAdministrador =
                in_array($usuario->role, [
                    'Gerente Ti',
                    'Soporte Tecnico'
                ], true) &&
                $usuario->priv_admin === 'Y';

            if ($esAdministrador) {
                return redirect()->route('tecnologias');
            }

            return $next($request);
        }

        if (!in_array($usuario->role, $roles, true)) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}