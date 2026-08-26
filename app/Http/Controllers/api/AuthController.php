<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $datosValidados = $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        $usuarioIngresado = trim($datosValidados['usuario']);

        Log::info('API LOGIN: Intento de inicio de sesión', [
            'usuario_ingresado' => $usuarioIngresado,
            'ip' => $request->ip(),
        ]);

        $usuario = User::where('email', $usuarioIngresado)
            ->orWhere('login', $usuarioIngresado)
            ->first();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario o la contraseña son incorrectos.',
            ], 401);
        }

        $estadoUsuario = strtoupper(
            trim((string) $usuario->active)
        );

        if ($estadoUsuario !== 'Y') {
            return response()->json([
                'success' => false,
                'message' => 'Tu usuario se encuentra desactivado.',
            ], 403);
        }

        if (!Hash::check(
            $datosValidados['password'],
            $usuario->getAuthPassword()
        )) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario o la contraseña son incorrectos.',
            ], 401);
        }

        if (
            strtoupper(
                trim((string) $usuario->mfa)
            ) === 'Y'
        ) {
            return response()->json([
                'success' => true,
                'mfa_required' => true,
                'message' => 'Se requiere autenticación de dos factores.',
                'login' => $usuario->login,
                'user' => $this->datosUsuario($usuario),
            ], 200);
        }

        $token = $usuario
            ->createToken('flutter')
            ->plainTextToken;

        return response()->json([
            'success' => true,
            'mfa_required' => false,
            'message' => 'Inicio de sesión correcto.',
            'token' => $token,
            'user' => $this->datosUsuario($usuario),
        ], 200);
    }

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

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente.',
        ], 200);
    }

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