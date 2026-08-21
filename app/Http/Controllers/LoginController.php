<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function Login(Request $request)
    {
        Log::info('==========================================');
        Log::info('LOGIN: Intento de inicio de sesión');
        Log::info('==========================================');

        /*
        |--------------------------------------------------------------------------
        | VALIDAR DATOS
        |--------------------------------------------------------------------------
        */

        $datosValidados = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $usuarioIngresado = $datosValidados['email'];

        $remember = $request->boolean('remember');

        Log::info('LOGIN: Datos recibidos', [
            'usuario_ingresado' => $usuarioIngresado,
            'remember' => $remember,
            'ip' => $request->ip(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | BUSCAR USUARIO
        |--------------------------------------------------------------------------
        |
        | Puede iniciar sesión utilizando:
        |
        | - login
        | - email
        |
        */

        $usuario = User::where('email', $usuarioIngresado)
            ->orWhere('login', $usuarioIngresado)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | USUARIO NO ENCONTRADO
        |--------------------------------------------------------------------------
        */

        if (!$usuario) {

            Log::warning(
                'LOGIN: Usuario no encontrado',
                [
                    'usuario_ingresado' => $usuarioIngresado,
                ]
            );

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'El usuario o la contraseña son incorrectos.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | INFORMACIÓN DEL USUARIO
        |--------------------------------------------------------------------------
        */

        Log::info('LOGIN: Usuario encontrado', [
            'login' => $usuario->login,
            'email' => $usuario->email,
            'active' => $usuario->active,
            'mfa' => $usuario->mfa,
        ]);

        /*
        |--------------------------------------------------------------------------
        | USUARIO ACTIVO
        |--------------------------------------------------------------------------
        */

        if ($usuario->active !== 'Y') {

            Log::warning(
                'LOGIN: Usuario desactivado',
                [
                    'login' => $usuario->login,
                ]
            );

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Tu usuario se encuentra desactivado.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDAR CONTRASEÑA
        |--------------------------------------------------------------------------
        |
        | IMPORTANTE:
        |
        | No usamos Auth::attempt() aquí porque eso iniciaría
        | la sesión antes de verificar MFA.
        |
        */

        if (
            !Auth::validate([
                'login' => $usuario->login,
                'password' => $datosValidados['password'],
            ])
        ) {

            Log::warning(
                'LOGIN: Contraseña incorrecta',
                [
                    'login' => $usuario->login,
                ]
            );

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'El usuario o la contraseña son incorrectos.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CREDENCIALES CORRECTAS
        |--------------------------------------------------------------------------
        */

        Log::info(
            'LOGIN: Credenciales correctas',
            [
                'login' => $usuario->login,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | MFA ACTIVADO
        |--------------------------------------------------------------------------
        */

        if ($usuario->mfa === 'Y') {

            Log::info(
                'LOGIN: MFA activado para usuario',
                [
                    'login' => $usuario->login,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | LIMPIAR CUALQUIER SESIÓN MFA ANTERIOR
            |--------------------------------------------------------------------------
            */

            $request->session()->forget([
                'mfa_pending',
                'mfa_login',
                'mfa_remember',

                /*
                | Eliminamos también la antigua variable por seguridad.
                */
                'mfa_pending_user_id',
            ]);

            /*
            |--------------------------------------------------------------------------
            | GUARDAR DATOS TEMPORALES DEL MFA
            |--------------------------------------------------------------------------
            |
            | IMPORTANTE:
            |
            | NO usamos user_id.
            |
            | El usuario se identifica mediante:
            |
            | mfa_login = jhinojosa
            |
            */

            $request->session()->put(
                'mfa_pending',
                true
            );

            $request->session()->put(
                'mfa_login',
                $usuario->login
            );

            $request->session()->put(
                'mfa_remember',
                $remember
            );

            /*
            |--------------------------------------------------------------------------
            | VERIFICAR QUE REALMENTE SE GUARDÓ
            |--------------------------------------------------------------------------
            */

            Log::info(
                'LOGIN: Sesión MFA creada',
                [
                    'mfa_pending' =>
                        $request->session()->get('mfa_pending'),

                    'mfa_login' =>
                        $request->session()->get('mfa_login'),

                    'mfa_remember' =>
                        $request->session()->get('mfa_remember'),

                    'session_id' =>
                        $request->session()->getId(),
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | MOSTRAR MODAL MFA
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('login')
                ->with(
                    'mfa_required',
                    true
                );
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN SIN MFA
        |--------------------------------------------------------------------------
        */

        Log::info(
            'LOGIN: Usuario no tiene MFA. Iniciando sesión.',
            [
                'login' => $usuario->login,
            ]
        );

        Auth::login(
            $usuario,
            $remember
        );

        /*
        |--------------------------------------------------------------------------
        | REGENERAR SESIÓN
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | REDIRECCIÓN
        |--------------------------------------------------------------------------
        */

        return $this->redireccionarUsuario($usuario);
    }


    /*
    |--------------------------------------------------------------------------
    | REDIRECCIÓN SEGÚN ROL
    |--------------------------------------------------------------------------
    */

    private function redireccionarUsuario($usuario)
    {
        Log::info(
            'LOGIN: Redireccionando usuario',
            [
                'login' => $usuario->login,
                'role' => $usuario->role,
                'priv_admin' => $usuario->priv_admin,
            ]
        );

        if (
            in_array($usuario->role, [
                'Gerente Ti',
                'Soporte Tecnico',
            ])
            && $usuario->priv_admin === 'Y'
        ) {

            return redirect()
                ->route('tecnologias');
        }

        return redirect()
            ->route('dashboard');
    }
}