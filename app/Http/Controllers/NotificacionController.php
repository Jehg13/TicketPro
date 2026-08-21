<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    public function marcarLeidas(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.'
            ], 401);
        }

        Notificacion::where('login', $usuario->login)
            ->where('leida', false)
            ->update([
                'leida' => true
            ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notificaciones marcadas como leídas.'
            ]);
        }

        return redirect()->back()->with(
            'success',
            'Notificaciones marcadas como leídas.'
        );
    }
}