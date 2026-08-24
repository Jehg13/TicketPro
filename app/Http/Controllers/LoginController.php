<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{
    public function Login(Request $request)
    {
        Log::info('==========================================');
        Log::info('LOGIN: Intento de inicio de sesión');
        Log::info('==========================================');

        $datosValidados = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $usuarioIngresado = trim($datosValidados['email']);
        $remember = $request->boolean('remember');

        Log::info('LOGIN: Datos recibidos', [
            'usuario_ingresado' => $usuarioIngresado,
            'remember' => $remember,
            'ip' => $request->ip(),
        ]);

        $usuario = User::where('email', $usuarioIngresado)
            ->orWhere('login', $usuarioIngresado)
            ->first();

        if (!$usuario) {
            Log::warning('LOGIN: Usuario no encontrado', [
                'usuario_ingresado' => $usuarioIngresado,
            ]);

            return redirect()
                ->route('login')
                ->with('error', 'El usuario o la contraseña son incorrectos.');
        }

        Log::info('LOGIN: Usuario encontrado', [
            'login' => $usuario->login,
            'email' => $usuario->email,
            'active' => $usuario->active,
            'mfa' => $usuario->mfa,
        ]);

        $estadoUsuario = strtoupper(trim((string) $usuario->active));

        if ($estadoUsuario !== 'Y') {
            Log::warning('LOGIN: Intento de acceso con usuario inactivo', [
                'login' => $usuario->login,
                'active' => $usuario->active,
            ]);

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('error', 'Tu usuario se encuentra desactivado.');
        }

        if (!Auth::validate([
            'login' => $usuario->login,
            'password' => $datosValidados['password'],
        ])) {
            Log::warning('LOGIN: Contraseña incorrecta', [
                'login' => $usuario->login,
            ]);

            return redirect()
                ->route('login')
                ->with('error', 'El usuario o la contraseña son incorrectos.');
        }

        Log::info('LOGIN: Credenciales correctas', [
            'login' => $usuario->login,
        ]);

        if (strtoupper(trim((string) $usuario->mfa)) === 'Y') {
            Log::info('LOGIN: MFA activado para usuario', [
                'login' => $usuario->login,
            ]);

            $request->session()->forget([
                'mfa_pending',
                'mfa_login',
                'mfa_remember',
                'mfa_pending_user_id',
            ]);

            $request->session()->put('mfa_pending', true);
            $request->session()->put('mfa_login', $usuario->login);
            $request->session()->put('mfa_remember', $remember);

            Log::info('LOGIN: Sesión MFA creada', [
                'mfa_pending' => $request->session()->get('mfa_pending'),
                'mfa_login' => $request->session()->get('mfa_login'),
                'mfa_remember' => $request->session()->get('mfa_remember'),
                'session_id' => $request->session()->getId(),
            ]);

            return redirect()
                ->route('login')
                ->with('mfa_required', true);
        }

        Log::info('LOGIN: Usuario no tiene MFA. Iniciando sesión.', [
            'login' => $usuario->login,
        ]);

        Auth::login($usuario, $remember);

        $request->session()->regenerate();

        return $this->redireccionarUsuario($usuario);
    }

    private function redireccionarUsuario($usuario)
    {
        Log::info('LOGIN: Redireccionando usuario', [
            'login' => $usuario->login,
            'role' => $usuario->role,
            'priv_admin' => $usuario->priv_admin,
        ]);

        if (
            in_array($usuario->role, [
                'Gerente Ti',
                'Soporte Tecnico',
            ]) &&
            strtoupper(trim((string) $usuario->priv_admin)) === 'Y'
        ) {
            return redirect()
                ->route('tecnologias');
        }

        return redirect()
            ->route('dashboard');
    }
}