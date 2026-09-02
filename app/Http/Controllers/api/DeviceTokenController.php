<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DeviceTokenController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        $validated = $request->validate([
            'token' => ['required', 'string', 'max:512'],
            'platform' => ['nullable', 'string', 'max:30'],
            'device_id' => ['nullable', 'string', 'max:255'],
            'app_version' => ['nullable', 'string', 'max:50'],
        ]);

        Log::info('FCM DEVICE TOKEN: registrando dispositivo', [
            'login' => $usuario->login,
            'platform' => $validated['platform'] ?? null,
        ]);

        $deviceToken = DeviceToken::updateOrCreate(
            ['token' => trim($validated['token'])],
            [
                'login' => $usuario->login,
                'platform' => $validated['platform'] ?? null,
                'device_id' => $validated['device_id'] ?? null,
                'app_version' => $validated['app_version'] ?? null,
                'last_seen_at' => now(),
                'revoked_at' => null,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Dispositivo registrado para notificaciones.',
            'data' => [
                'id' => $deviceToken->id,
                'platform' => $deviceToken->platform,
                'last_seen_at' => $deviceToken->last_seen_at,
            ],
        ], 201);
    }

    public function destroy(Request $request): JsonResponse
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        $validated = $request->validate([
            'token' => ['required', 'string', 'max:512'],
        ]);

        DeviceToken::where('login', $usuario->login)
            ->where('token', trim($validated['token']))
            ->update(['revoked_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Dispositivo retirado de las notificaciones.',
        ]);
    }
}
