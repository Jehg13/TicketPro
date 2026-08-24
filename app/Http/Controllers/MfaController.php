<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FA\Google2FA;

class MfaController extends Controller
{
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
        $secretKey = $google2fa->generateSecretKey();

        session()->put('mfa_setup_secret', $secretKey);

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

    public function verificarActivacion(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        Log::info('MFA: Verificando activación', [
            'login' => $user->login,
        ]);

        $request->validate([
            'codigo' => [
                'required',
                'digits:6',
            ],
        ], [
            'codigo.required' => 'Debes ingresar el código de Google Authenticator.',
            'codigo.digits' => 'El código debe contener 6 dígitos.',
        ]);

        $secretKey = session('mfa_setup_secret');

        if (!$secretKey) {
            Log::warning('MFA: No existe mfa_setup_secret.', [
                'login' => $user->login,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'La configuración de MFA expiró. Intenta nuevamente.',
            ], 422);
        }

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
                'message' => 'El código de Google Authenticator no es correcto.',
            ], 422);
        }

        $user->mfa = 'Y';
        $user->activation_code = $secretKey;
        $user->mfa_last_updated = now();
        $user->save();

        session()->forget('mfa_setup_secret');

        Log::info('MFA: Activado correctamente', [
            'login' => $user->login,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'La verificación en dos pasos fue activada correctamente.',
        ]);
    }

    public function mostrarVerificacion()
    {
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

    public function verificar(Request $request)
    {
        Log::info('==========================================');
        Log::info('MFA LOGIN: ENTRÓ A verificar()');
        Log::info('==========================================');

        Log::info('MFA LOGIN: POST recibido', [
            'codigo' => $request->input('codigo'),
            'ip' => $request->ip(),
            'session_id' => $request->session()->getId(),
        ]);

        $mfaPending = session('mfa_pending');
        $mfaLogin = session('mfa_login');
        $mfaRemember = session('mfa_remember', false);

        Log::info('MFA LOGIN: Sesión', [
            'mfa_pending' => $mfaPending,
            'mfa_login' => $mfaLogin,
            'mfa_remember' => $mfaRemember,
        ]);

        if (!$mfaPending || !$mfaLogin) {
            Log::error('MFA LOGIN: No existe información de login MFA.');

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

        $request->validate([
            'codigo' => [
                'required',
                'digits:6',
            ],
        ], [
            'codigo.required' => 'Debes ingresar el código de Google Authenticator.',
            'codigo.digits' => 'El código debe contener 6 dígitos.',
        ]);

        $usuario = User::where('login', $mfaLogin)->first();

        Log::info('MFA LOGIN: Búsqueda de usuario', [
            'mfa_login' => $mfaLogin,
            'usuario_encontrado' => $usuario ? true : false,
        ]);

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

        Log::info('MFA LOGIN: Usuario encontrado', [
            'login' => $usuario->login,
            'email' => $usuario->email,
            'mfa' => $usuario->mfa,
            'tiene_activation_code' => !empty($usuario->activation_code),
            'activation_code_length' => $usuario->activation_code
                ? strlen($usuario->activation_code)
                : 0,
        ]);

        if ($usuario->mfa !== 'Y') {
            Log::warning('MFA LOGIN: MFA no está activo.', [
                'login' => $usuario->login,
                'mfa' => $usuario->mfa,
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
                    'La autenticación de dos factores no está activa.'
                );
        }

        if (!$usuario->activation_code) {
            Log::error('MFA LOGIN: activation_code vacío.', [
                'login' => $usuario->login,
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
                    'El usuario no tiene configurada la clave MFA.'
                );
        }

        $codigo = $request->input('codigo');

        Log::info('MFA LOGIN: Código recibido', [
            'login' => $usuario->login,
            'longitud' => strlen($codigo),
        ]);

        $google2fa = new Google2FA();

        $valido = $google2fa->verifyKey(
            $usuario->activation_code,
            $codigo
        );

        Log::info('MFA LOGIN: Resultado Google Authenticator', [
            'login' => $usuario->login,
            'valido' => $valido,
        ]);

        if (!$valido) {
            Log::warning('MFA LOGIN: Código incorrecto.', [
                'login' => $usuario->login,
            ]);

            return redirect()
                ->route('login')
                ->with('mfa_required', true)
                ->with(
                    'mfa_error',
                    'El código de Google Authenticator no es correcto o ya expiró.'
                );
        }

        Log::info('MFA LOGIN: Código correcto.', [
            'login' => $usuario->login,
        ]);

        Auth::login(
            $usuario,
            $mfaRemember
        );

        Log::info('MFA LOGIN: Auth::login ejecutado.', [
            'login' => $usuario->login,
            'authenticated' => Auth::check(),
        ]);

        $request->session()->regenerate();

        $request->session()->forget([
            'mfa_pending',
            'mfa_login',
            'mfa_remember',
        ]);

        Log::info('MFA LOGIN: Sesión MFA temporal eliminada.');

        Log::info('MFA LOGIN: Redireccionando usuario', [
            'login' => $usuario->login,
            'role' => $usuario->role,
            'priv_admin' => $usuario->priv_admin,
        ]);

        if (
            in_array($usuario->role, [
                'Gerente Ti',
                'Soporte Tecnico',
            ]) &&
            $usuario->priv_admin === 'Y'
        ) {
            Log::info('MFA LOGIN: Redirección -> tecnologias');

            return redirect()
                ->route('tecnologias');
        }

        Log::info('MFA LOGIN: Redirección -> dashboard');

        return redirect()
            ->route('dashboard');
    }

    public function desactivar(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no autenticado.'
        ], 401);
    }

    $request->validate([
        'codigo' => ['required', 'digits:6'],
    ], [
        'codigo.required' => 'Debes ingresar el código de Google Authenticator.',
        'codigo.digits' => 'El código debe contener 6 dígitos.',
    ]);

    if ($user->mfa !== 'Y') {
        return response()->json([
            'success' => false,
            'message' => 'La verificación en dos pasos ya está desactivada.'
        ], 422);
    }

    if (!$user->activation_code) {
        return response()->json([
            'success' => false,
            'message' => 'No existe una configuración MFA válida.'
        ], 422);
    }

    $google2fa = new Google2FA();

    $valido = $google2fa->verifyKey(
        $user->activation_code,
        $request->input('codigo')
    );

    if (!$valido) {
        return response()->json([
            'success' => false,
            'message' => 'El código de Google Authenticator no es correcto o ya expiró.'
        ], 422);
    }

    $user->mfa = 'N';
    $user->activation_code = null;
    $user->mfa_last_updated = now();
    $user->save();

    session()->forget('mfa_setup_secret');

    $redirect = (
        in_array($user->role, ['Gerente Ti', 'Soporte Tecnico']) &&
        $user->priv_admin === 'Y'
    )
        ? route('perfiltecnologias')
        : route('perfilusuario');

    return response()->json([
        'success' => true,
        'message' => 'La verificación en dos pasos fue desactivada correctamente.',
        'redirect' => $redirect
    ]);
}
}