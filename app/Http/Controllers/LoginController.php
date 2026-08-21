<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    public function Login(Request $request)
    {
        $datosValidados = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        /*
        |--------------------------------------------------------------------------
        | Buscar por email O login
        |--------------------------------------------------------------------------
        */

        $usuario = User::where('email', $datosValidados['email'])
            ->orWhere('login', $datosValidados['email'])
            ->first();

        if (!$usuario) {
            return redirect()
                ->route('login')
                ->with('error', 'El usuario o la contraseña son incorrectos');
        }

        /*
        |--------------------------------------------------------------------------
        | Verificar que esté activo
        |--------------------------------------------------------------------------
        */

        if ($usuario->active !== 'Y') {
            return redirect()
                ->route('login')
                ->with('error', 'Tu usuario se encuentra desactivado');
        }

        /*
        |--------------------------------------------------------------------------
        | Autenticar
        |--------------------------------------------------------------------------
        */

        if (!Auth::attempt([
            'login' => $usuario->login,
            'password' => $datosValidados['password'],
        ], $remember)) {

            return redirect()
                ->route('login')
                ->with('error', 'El usuario o la contraseña son incorrectos');
        }

        /*
        |--------------------------------------------------------------------------
        | Regenerar sesión
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        $usuario = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Redireccionar según role
        |--------------------------------------------------------------------------
        */

        return match ($usuario->role) {
            'usuario' => redirect()->route('dashboard'),
            'tecnologias' => redirect()->route('tecnologias'),
            default => abort(403, 'Rol de usuario inválido'),
        };
    }
}