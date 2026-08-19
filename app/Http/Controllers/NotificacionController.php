<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    public function marcarLeidas()
    {
        Notificacion::where('user_id', Auth::id())
            ->where('leida', false)
            ->update([
                'leida' => true
            ]);

        return response()->json([
            'success' => true
        ]);
    }
}