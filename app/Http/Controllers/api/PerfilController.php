<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\SolicitudCambio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function show()
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.'
            ], 401);
        }

        $numeroEmpleado = DB::table('numeros_empleado')
            ->where('login', $usuario->login)
            ->value('numero_empleado');

        $departamento = $usuario->departamento;
        $oficina = $departamento?->oficina;

        return response()->json([
            'success' => true,
            'usuario' => [
                'login' => $usuario->login,
                'name' => $usuario->name,
                'email' => $usuario->email,
                'phone' => $usuario->phone,
                'picture' => $usuario->picture
                    ? asset('storage/' . $usuario->picture)
                    : asset('images/user.png'),
                'numero_empleado' => $numeroEmpleado,
                'departamento' => $departamento?->nombre,
                'oficina' => $oficina?->nombre,
                'role' => $usuario->role,
            ]
        ]);
    }

    public function actualizarPassword(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.'
            ], 401);
        }

        $request->validate([
            'password_actual' => [
                'required',
                'string'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ]
        ]);

        $passwordGuardada = $usuario->pswd;

        if (!$passwordGuardada) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró una contraseña válida para tu usuario.'
            ], 422);
        }

        $esBcrypt = is_string($passwordGuardada)
            && (
                str_starts_with($passwordGuardada, '$2y$')
                || str_starts_with($passwordGuardada, '$2a$')
                || str_starts_with($passwordGuardada, '$2b$')
            );

        if ($esBcrypt) {
            $passwordActualCorrecta = Hash::check(
                $request->password_actual,
                $passwordGuardada
            );
        } else {
            $passwordActualCorrecta = hash_equals(
                (string) $passwordGuardada,
                (string) $request->password_actual
            );
        }

        if (!$passwordActualCorrecta) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña actual no es correcta.'
            ], 422);
        }

        if ($esBcrypt) {
            $mismaPassword = Hash::check(
                $request->password,
                $passwordGuardada
            );
        } else {
            $mismaPassword = hash_equals(
                (string) $passwordGuardada,
                (string) $request->password
            );
        }

        if ($mismaPassword) {
            return response()->json([
                'success' => false,
                'message' => 'La nueva contraseña debe ser diferente a la contraseña actual.'
            ], 422);
        }

        $usuario->pswd = Hash::make($request->password);
        $usuario->pswd_last_updated = now();
        $usuario->save();

        Auth::logout();

        return response()->json([
            'success' => true,
            'message' => 'Tu contraseña fue actualizada correctamente. Inicia sesión nuevamente.'
        ]);
    }

    public function updateFoto(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.'
            ], 401);
        }

        $request->validate([
            'picture' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048'
            ]
        ]);

        if (
            $usuario->picture &&
            $usuario->picture !== 'profile-photos/user.png'
        ) {
            Storage::disk('public')->delete($usuario->picture);
        }

        $path = $request
            ->file('picture')
            ->store('profile-photos', 'public');

        $usuario->picture = $path;
        $usuario->save();

        return response()->json([
            'success' => true,
            'message' => 'Foto de perfil actualizada correctamente.',
            'picture' => asset('storage/' . $path)
        ]);
    }

    public function deleteFoto()
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.'
            ], 401);
        }

        if (
            $usuario->picture &&
            $usuario->picture !== 'profile-photos/user.png'
        ) {
            Storage::disk('public')->delete($usuario->picture);
        }

        $usuario->picture = 'profile-photos/user.png';
        $usuario->save();

        return response()->json([
            'success' => true,
            'message' => 'Foto de perfil eliminada correctamente.',
            'picture' => asset('images/user.png')
        ]);
    }

    public function solicitarCambio(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.'
            ], 401);
        }

        $request->validate([
            'campo' => [
                'required',
                'string',
                'in:nombre,correo,oficina,departamento,telefono,usuario,numeroempleado,role'
            ],
            'nuevo_valor' => [
                'required',
                'string',
                'max:255'
            ],
            'motivo' => [
                'required',
                'string',
                'max:1000'
            ]
        ]);

        switch ($request->campo) {
            case 'nombre':
                $valorActual = $usuario->name;
                break;

            case 'correo':
                $valorActual = $usuario->email;
                break;

            case 'oficina':
                $valorActual = $usuario->departamento?->oficina?->nombre;
                break;

            case 'departamento':
                $valorActual = $usuario->departamento?->nombre;
                break;

            case 'telefono':
                $valorActual = $usuario->phone;
                break;

            case 'usuario':
                $valorActual = $usuario->login;
                break;

            case 'numeroempleado':
                $valorActual = DB::table('numeros_empleado')
                    ->where('login', $usuario->login)
                    ->value('numero_empleado');
                break;

            case 'role':
                $valorActual = $usuario->role;
                break;

            default:
                $valorActual = null;
                break;
        }

        $solicitud = SolicitudCambio::create([
            'login' => $usuario->login,
            'campo' => $request->campo,
            'valor_actual' => $valorActual,
            'nuevo_valor' => $request->nuevo_valor,
            'motivo' => $request->motivo,
            'estado' => 'pendiente'
        ]);

        $administradores = \App\Models\User::whereIn(
            'role',
            [
                'Gerente Ti',
                'Soporte Tecnico'
            ]
        )
            ->where('priv_admin', 'Y')
            ->where('login', '!=', $usuario->login)
            ->get();

        $nombresCampos = [
            'nombre' => 'nombre completo',
            'correo' => 'correo electrónico',
            'oficina' => 'oficina / sucursal',
            'departamento' => 'departamento',
            'telefono' => 'teléfono',
            'usuario' => 'usuario de acceso',
            'numeroempleado' => 'número de empleado',
            'role' => 'rol'
        ];

        $nombreCampo =
            $nombresCampos[$request->campo]
            ?? $request->campo;

        foreach ($administradores as $administrador) {
            Notificacion::create([
                'login' => $administrador->login,
                'tipo' => 'solicitud_cambio',
                'titulo' => 'Nueva solicitud de cambio',
                'mensaje' =>
                    $usuario->name .
                    ' solicitó cambiar su ' .
                    $nombreCampo . '.',
                'url' => route(
                    'cambiostecnologias',
                    [
                        'solicitud' => $solicitud->id
                    ]
                ),
                'leida' => false,
                'icono' => 'user-cog',
                'color' => 'blue'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tu solicitud de cambio fue enviada correctamente.',
            'solicitud_id' => $solicitud->id
        ], 201);
    }
}