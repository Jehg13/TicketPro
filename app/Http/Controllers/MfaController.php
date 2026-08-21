<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use PragmaRX\Google2FA\Google2FA;

class MfaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CONFIGURAR MFA
    |--------------------------------------------------------------------------
    */

    public function configurar()
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        Log::info('MFA: Iniciando configuración', [
            'login' => $user->login,
            'email' => $user->email,
        ]);

        $google2fa = new Google2FA();

        /*
        |--------------------------------------------------------------------------
        | GENERAR SECRET
        |--------------------------------------------------------------------------
        */

        $secretKey = $google2fa->generateSecretKey();

        /*
        |--------------------------------------------------------------------------
        | GUARDAR SECRET TEMPORALMENTE
        |--------------------------------------------------------------------------
        */

        session()->put('mfa_setup_secret', $secretKey);

        /*
        |--------------------------------------------------------------------------
        | GENERAR QR
        |--------------------------------------------------------------------------
        */

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            'TicketPro',
            $user->email,
            $secretKey
        );

        Log::info('MFA: Secret generado correctamente', [
            'login' => $user->login,
            'secret_length' => strlen($secretKey),
        ]);

        return response()->json([
            'success' => true,
            'qrCodeUrl' => $qrCodeUrl,
            'secretKey' => $secretKey,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR ACTIVACIÓN MFA
    |--------------------------------------------------------------------------
    */

    public function verificarActivacion(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        Log::info('MFA: Verificando activación', [
            'login' => $user->login,
            'codigo' => $request->input('codigo'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | VALIDAR CÓDIGO
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'codigo' => [
                'required',
                'digits:6',
            ],
        ], [
            'codigo.required' =>
                'Debes ingresar el código de Google Authenticator.',

            'codigo.digits' =>
                'El código debe contener 6 dígitos.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | OBTENER SECRET TEMPORAL
        |--------------------------------------------------------------------------
        */

        $secretKey = session('mfa_setup_secret');

        if (!$secretKey) {

            Log::warning('MFA: No existe mfa_setup_secret.', [
                'login' => $user->login,
            ]);

            return response()->json([
                'success' => false,
                'message' =>
                    'La configuración de MFA expiró. Intenta nuevamente.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | VERIFICAR GOOGLE AUTHENTICATOR
        |--------------------------------------------------------------------------
        */

        $google2fa = new Google2FA();

        $valido = $google2fa->verifyKey(
            $secretKey,
            $request->input('codigo')
        );

        Log::info('MFA: Resultado activación', [
            'login' => $user->login,
            'valido' => $valido,
        ]);

        if (!$valido) {

            return response()->json([
                'success' => false,
                'message' =>
                    'El código de Google Authenticator no es correcto.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | ACTIVAR MFA
        |--------------------------------------------------------------------------
        */

        $user->mfa = 'Y';

        /*
        | IMPORTANTE:
        | Guardamos aquí el SECRET, NO el código de 6 dígitos.
        */

        $user->activation_code = $secretKey;

        $user->mfa_last_updated = now();

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | ELIMINAR SECRET TEMPORAL
        |--------------------------------------------------------------------------
        */

        session()->forget('mfa_setup_secret');

        Log::info('MFA: Activado correctamente', [
            'login' => $user->login,
        ]);

        return response()->json([
            'success' => true,
            'message' =>
                'La verificación en dos pasos fue activada correctamente.',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | MOSTRAR VERIFICACIÓN MFA
    |--------------------------------------------------------------------------
    */

    public function mostrarVerificacion()
    {
        /*
        |--------------------------------------------------------------------------
        | AQUÍ YA NO BUSCAMOS mfa_pending_user_id
        |--------------------------------------------------------------------------
        */

        if (!session()->has('mfa_login')) {

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'La sesión de verificación MFA expiró. Inicia sesión nuevamente.'
                );
        }

        return view('auth.mfa-verificar');
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR MFA DURANTE LOGIN
    |--------------------------------------------------------------------------
    */

    public function verificar(Request $request)
    {
        Log::info('==========================================');
        Log::info('MFA LOGIN: ENTRÓ A verificar()');
        Log::info('==========================================');

        /*
        |--------------------------------------------------------------------------
        | DATOS RECIBIDOS
        |--------------------------------------------------------------------------
        */

        Log::info('MFA LOGIN: POST recibido', [
            'codigo' => $request->input('codigo'),
            'ip' => $request->ip(),
            'session_id' => $request->session()->getId(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | DATOS DE SESIÓN
        |--------------------------------------------------------------------------
        */

        $mfaPending = session('mfa_pending');

        $mfaLogin = session('mfa_login');

        $mfaRemember = session('mfa_remember', false);

        Log::info('MFA LOGIN: Sesión', [
            'mfa_pending' => $mfaPending,
            'mfa_login' => $mfaLogin,
            'mfa_remember' => $mfaRemember,
        ]);

        /*
        |--------------------------------------------------------------------------
        | COMPROBAR LOGIN PENDIENTE
        |--------------------------------------------------------------------------
        */

        if (!$mfaPending || !$mfaLogin) {

            Log::error(
                'MFA LOGIN: No existe información de login MFA.'
            );

            session()->forget([
                'mfa_pending',
                'mfa_login',
                'mfa_remember',
            ]);

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'La sesión de autenticación expiró. Inicia sesión nuevamente.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDAR CÓDIGO
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'codigo' => [
                'required',
                'digits:6',
            ],
        ], [
            'codigo.required' =>
                'Debes ingresar el código de Google Authenticator.',

            'codigo.digits' =>
                'El código debe contener 6 dígitos.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | BUSCAR USUARIO POR LOGIN
        |--------------------------------------------------------------------------
        */

        $usuario = User::where('login', $mfaLogin)->first();

        Log::info('MFA LOGIN: Búsqueda de usuario', [
            'mfa_login' => $mfaLogin,
            'usuario_encontrado' => $usuario ? true : false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | USUARIO NO ENCONTRADO
        |--------------------------------------------------------------------------
        */

        if (!$usuario) {

            Log::error('MFA LOGIN: Usuario no encontrado.', [
                'login' => $mfaLogin,
            ]);

            session()->forget([
                'mfa_pending',
                'mfa_login',
                'mfa_remember',
            ]);

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'No se pudo encontrar el usuario.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | INFORMACIÓN DEL USUARIO
        |--------------------------------------------------------------------------
        */

        Log::info('MFA LOGIN: Usuario encontrado', [
            'login' => $usuario->login,
            'email' => $usuario->email,
            'mfa' => $usuario->mfa,
            'tiene_activation_code' =>
                !empty($usuario->activation_code),
            'activation_code_length' =>
                $usuario->activation_code
                    ? strlen($usuario->activation_code)
                    : 0,
        ]);

        /*
        |--------------------------------------------------------------------------
        | COMPROBAR MFA ACTIVO
        |--------------------------------------------------------------------------
        */

        if ($usuario->mfa !== 'Y') {

            Log::warning(
                'MFA LOGIN: MFA no está activo.',
                [
                    'login' => $usuario->login,
                    'mfa' => $usuario->mfa,
                ]
            );

            session()->forget([
                'mfa_pending',
                'mfa_login',
                'mfa_remember',
            ]);

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'La autenticación de dos factores no está activa.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | COMPROBAR SECRET MFA
        |--------------------------------------------------------------------------
        */

        if (!$usuario->activation_code) {

            Log::error(
                'MFA LOGIN: activation_code vacío.',
                [
                    'login' => $usuario->login,
                ]
            );

            session()->forget([
                'mfa_pending',
                'mfa_login',
                'mfa_remember',
            ]);

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'El usuario no tiene configurada la clave MFA.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CÓDIGO RECIBIDO
        |--------------------------------------------------------------------------
        */

        $codigo = $request->input('codigo');

        Log::info('MFA LOGIN: Código recibido', [
            'login' => $usuario->login,
            'longitud' => strlen($codigo),
        ]);

        /*
        |--------------------------------------------------------------------------
        | VERIFICAR GOOGLE AUTHENTICATOR
        |--------------------------------------------------------------------------
        */

        $google2fa = new Google2FA();

        $valido = $google2fa->verifyKey(
            $usuario->activation_code,
            $codigo
        );

        Log::info(
            'MFA LOGIN: Resultado Google Authenticator',
            [
                'login' => $usuario->login,
                'valido' => $valido,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | CÓDIGO INCORRECTO
        |--------------------------------------------------------------------------
        */

        if (!$valido) {

            Log::warning(
                'MFA LOGIN: Código incorrecto.',
                [
                    'login' => $usuario->login,
                ]
            );

            return redirect()
                ->route('login')
                ->with('mfa_required', true)
                ->with(
                    'mfa_error',
                    'El código de Google Authenticator no es correcto o ya expiró.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | MFA CORRECTO
        |--------------------------------------------------------------------------
        */

        Log::info(
            'MFA LOGIN: Código correcto.',
            [
                'login' => $usuario->login,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | INICIAR SESIÓN REAL
        |--------------------------------------------------------------------------
        */

        Auth::login(
            $usuario,
            $mfaRemember
        );

        Log::info(
            'MFA LOGIN: Auth::login ejecutado.',
            [
                'login' => $usuario->login,
                'authenticated' => Auth::check(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | REGENERAR SESIÓN
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | LIMPIAR DATOS TEMPORALES
        |--------------------------------------------------------------------------
        */

        $request->session()->forget([
            'mfa_pending',
            'mfa_login',
            'mfa_remember',
        ]);

        Log::info(
            'MFA LOGIN: Sesión MFA temporal eliminada.'
        );

        /*
        |--------------------------------------------------------------------------
        | REDIRECCIÓN
        |--------------------------------------------------------------------------
        */

        Log::info('MFA LOGIN: Redireccionando usuario', [
            'login' => $usuario->login,
            'role' => $usuario->role,
            'priv_admin' => $usuario->priv_admin,
        ]);

        if (
            in_array($usuario->role, [
                'Gerente Ti',
                'Soporte Tecnico',
            ])
            && $usuario->priv_admin === 'Y'
        ) {

            Log::info(
                'MFA LOGIN: Redirección -> tecnologias'
            );

            return redirect()
                ->route('tecnologias');
        }

        Log::info(
            'MFA LOGIN: Redirección -> dashboard'
        );

        return redirect()
            ->route('dashboard');
    }


    /*
    |--------------------------------------------------------------------------
    | DESACTIVAR MFA
    |--------------------------------------------------------------------------
    */

    public function desactivar()
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        Log::info('MFA: Desactivando MFA', [
            'login' => $user->login,
        ]);

        /*
        |--------------------------------------------------------------------------
        | DESACTIVAR
        |--------------------------------------------------------------------------
        */

        $user->mfa = 'N';

        $user->activation_code = null;

        $user->mfa_last_updated = now();

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | LIMPIAR SESIÓN
        |--------------------------------------------------------------------------
        */

        session()->forget('mfa_setup_secret');

        Log::info('MFA: MFA desactivado correctamente', [
            'login' => $user->login,
        ]);

        return response()->json([
            'success' => true,
            'message' =>
                'La verificación en dos pasos fue desactivada correctamente.',
        ]);
    }
}