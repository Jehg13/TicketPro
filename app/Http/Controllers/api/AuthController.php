<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Login para la aplicación Flutter.
     *
     * Permite iniciar sesión utilizando:
     * - email
     * - login
     */
    public function login(Request $request)
    {
        Log::info('==========================================');
        Log::info('API LOGIN: Intento de inicio de sesión');
        Log::info('==========================================');

        $datosValidados = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $usuarioIngresado = trim($datosValidados['email']);

        Log::info('API LOGIN: Datos recibidos', [
            'usuario_ingresado' => $usuarioIngresado,
            'ip' => $request->ip(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Buscar usuario
        |--------------------------------------------------------------------------
        */

        $usuario = User::where('email', $usuarioIngresado)
            ->orWhere('login', $usuarioIngresado)
            ->first();

        if (!$usuario) {

            Log::warning('API LOGIN: Usuario no encontrado', [
                'usuario_ingresado' => $usuarioIngresado,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'El usuario o la contraseña son incorrectos.',
            ], 401);
        }

        Log::info('API LOGIN: Usuario encontrado', [
            'login' => $usuario->login,
            'email' => $usuario->email,
            'active' => $usuario->active,
            'mfa' => $usuario->mfa,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Verificar usuario activo
        |--------------------------------------------------------------------------
        */

        $estadoUsuario = strtoupper(
            trim((string) $usuario->active)
        );

        if ($estadoUsuario !== 'Y') {

            Log::warning(
                'API LOGIN: Intento de acceso con usuario inactivo',
                [
                    'login' => $usuario->login,
                    'active' => $usuario->active,
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Tu usuario se encuentra desactivado.',
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | Verificar contraseña
        |--------------------------------------------------------------------------
        */

        if (!Hash::check(
            $datosValidados['password'],
            $usuario->getAuthPassword()
        )) {

            Log::warning(
                'API LOGIN: Contraseña incorrecta',
                [
                    'login' => $usuario->login,
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'El usuario o la contraseña son incorrectos.',
            ], 401);
        }

        Log::info('API LOGIN: Credenciales correctas', [
            'login' => $usuario->login,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Verificar MFA
        |--------------------------------------------------------------------------
        */

        if (
            strtoupper(
                trim((string) $usuario->mfa)
            ) === 'Y'
        ) {

            Log::info(
                'API LOGIN: MFA requerido',
                [
                    'login' => $usuario->login,
                ]
            );

            /*
             * No generamos todavía el token.
             *
             * Flutter tendrá que solicitar el código MFA.
             */

            return response()->json([
                'success' => true,
                'mfa_required' => true,
                'message' => 'Se requiere autenticación de dos factores.',
                'login' => $usuario->login,
            ], 200);
        }

        /*
        |--------------------------------------------------------------------------
        | Login sin MFA
        |--------------------------------------------------------------------------
        */

        Log::info(
            'API LOGIN: Usuario sin MFA. Generando token.',
            [
                'login' => $usuario->login,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Crear token Sanctum
        |--------------------------------------------------------------------------
        */

        $token = $usuario
            ->createToken('flutter')
            ->plainTextToken;

        Log::info(
            'API LOGIN: Token generado correctamente',
            [
                'login' => $usuario->login,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Respuesta
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
            'mfa_required' => false,
            'message' => 'Inicio de sesión correcto.',
            'token' => $token,
            'user' => $this->datosUsuario($usuario),
        ], 200);
    }


    /**
     * Obtener información del usuario autenticado.
     */
    public function user(Request $request)
    {
        $usuario = $request->user();

        if (!$usuario) {

            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => $this->datosUsuario($usuario),
        ], 200);
    }


    /**
     * Cerrar sesión.
     */
    public function logout(Request $request)
    {
        $usuario = $request->user();

        if (!$usuario) {

            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        $token = $usuario->currentAccessToken();

        if ($token) {
            $token->delete();
        }

        Log::info(
            'API LOGOUT: Sesión cerrada',
            [
                'login' => $usuario->login,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente.',
        ], 200);
    }


    /**
     * Datos que se devolverán a Flutter.
     */
    private function datosUsuario(User $usuario)
    {
        return [
            'login' => $usuario->login,
            'email' => $usuario->email,
            'name' => $usuario->name,
            'role' => $usuario->role,
            'priv_admin' => $usuario->priv_admin,
            'active' => $usuario->active,
            'mfa' => $usuario->mfa,
        ];
    }
}