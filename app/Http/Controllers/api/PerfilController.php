<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\SolicitudCambio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PerfilController extends Controller
{
    public function show()
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'usuario' => $this->datosUsuario($usuario),
        ]);
    }

    public function actualizarPerfilAdmin(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.',
            ], 401);
        }

        if (!$this->esAdministradorTI($usuario)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos administrativos para modificar esta información.',
            ], 403);
        }

        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'login' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'departamento' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string'],
            'numero_empleado' => ['required', 'string', 'max:100'],
        ]);

        $loginSolicitado = strtolower(trim((string) $datos['login']));
        $emailSolicitado = strtolower(trim((string) $datos['email']));
        $loginActual = strtolower(trim((string) $usuario->login));
        $emailActual = strtolower(trim((string) $usuario->email));
        $role = trim((string) $datos['role']);

        if (!in_array($role, ['Gerente Ti', 'Soporte Tecnico'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'El rol seleccionado no es válido.',
            ], 422);
        }

        if ($loginSolicitado !== $loginActual && User::whereRaw('LOWER(login) = ?', [$loginSolicitado])
            ->whereKeyNot($usuario->id)
            ->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'El login ya está registrado en el sistema.',
            ], 422);
        }

        if ($emailSolicitado !== $emailActual && User::whereRaw('LOWER(email) = ?', [$emailSolicitado])
            ->whereKeyNot($usuario->id)
            ->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'El correo electrónico ya está registrado en el sistema.',
            ], 422);
        }

        DB::beginTransaction();

        try {
            $loginAnterior = $usuario->login;
            $nuevoLogin = trim($datos['login']);
            $numeroEmpleado = trim($datos['numero_empleado']);

            $usuario->name = trim($datos['name']);
            $usuario->login = $nuevoLogin;
            $usuario->email = trim($datos['email']);
            $usuario->phone = isset($datos['phone']) && $datos['phone'] !== ''
                ? trim($datos['phone'])
                : null;
            $usuario->role = $datos['role'];
            $usuario->save();

            if ($usuario->departamento) {
                $usuario->departamento->nombre = trim($datos['departamento']);
                $usuario->departamento->save();
            }

            $registroNumero = DB::table('numeros_empleado')
                ->where('login', $loginAnterior)
                ->first();

            if ($registroNumero) {
                DB::table('numeros_empleado')
                    ->where('login', $loginAnterior)
                    ->update([
                        'login' => $nuevoLogin,
                        'numero_empleado' => $numeroEmpleado,
                    ]);
            } else {
                DB::table('numeros_empleado')->insert([
                    'login' => $nuevoLogin,
                    'numero_empleado' => $numeroEmpleado,
                ]);
            }

            if ($loginAnterior !== $nuevoLogin) {
                Notificacion::where('login', $loginAnterior)
                    ->update(['login' => $nuevoLogin]);
            }

            DB::commit();

            $usuario->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Información personal y laboral actualizada correctamente.',
                'usuario' => $this->datosUsuario($usuario),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar la información.',
            ], 500);
        }
    }

    public function actualizarPassword(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.',
            ], 401);
        }

        $datos = $request->validate([
            'password_actual' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $passwordGuardada = $usuario->pswd;

        if (!$passwordGuardada) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró una contraseña válida para tu usuario.',
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
                $datos['password_actual'],
                $passwordGuardada
            );
        } else {
            $passwordActualCorrecta = hash_equals(
                (string) $passwordGuardada,
                (string) $datos['password_actual']
            );
        }

        if (!$passwordActualCorrecta) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña actual no es correcta.',
            ], 422);
        }

        if ($esBcrypt) {
            $mismaPassword = Hash::check(
                $datos['password'],
                $passwordGuardada
            );
        } else {
            $mismaPassword = hash_equals(
                (string) $passwordGuardada,
                (string) $datos['password']
            );
        }

        if ($mismaPassword) {
            return response()->json([
                'success' => false,
                'message' => 'La nueva contraseña debe ser diferente a la contraseña actual.',
            ], 422);
        }

        $usuario->pswd = Hash::make($datos['password']);
        $usuario->pswd_last_updated = now();
        $usuario->save();

        Auth::logout();

        return response()->json([
            'success' => true,
            'message' => 'Tu contraseña fue actualizada correctamente. Inicia sesión nuevamente.',
        ]);
    }

    public function updateFoto(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.',
            ], 401);
        }

        $request->validate([
            'picture' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
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
        $usuario->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Foto de perfil actualizada correctamente.',
            'picture' => asset('storage/' . $path),
            'usuario' => $this->datosUsuario($usuario),
        ]);
    }

    public function deleteFoto()
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.',
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
        $usuario->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Foto de perfil eliminada correctamente.',
            'picture' => asset('images/user.png'),
            'usuario' => $this->datosUsuario($usuario),
        ]);
    }

    private function esAdministradorTI(User $usuario): bool
    {
        return in_array(
            $usuario->role,
            ['Gerente Ti', 'Soporte Tecnico'],
            true
        ) && $usuario->priv_admin === 'Y';
    }

    public function solicitarCambio(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.',
            ], 401);
        }

        $datos = $request->validate([
            'campo' => [
                'required',
                'string',
                'in:nombre,correo,oficina,departamento,telefono,usuario,numeroempleado,role',
            ],
            'nuevo_valor' => [
                'required',
                'string',
                'max:255',
            ],
            'motivo' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        switch ($datos['campo']) {
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
            'campo' => $datos['campo'],
            'valor_actual' => $valorActual,
            'nuevo_valor' => $datos['nuevo_valor'],
            'motivo' => $datos['motivo'],
            'estado' => 'pendiente',
        ]);

        $administradores = User::whereIn(
            'role',
            [
                'Gerente Ti',
                'Soporte Tecnico',
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
            'role' => 'rol',
        ];

        $nombreCampo = $nombresCampos[$datos['campo']]
            ?? $datos['campo'];

        foreach ($administradores as $administrador) {
            Notificacion::create([
                'login' => $administrador->login,
                'tipo' => 'solicitud_cambio',
                'titulo' => 'Nueva solicitud de cambio',
                'mensaje' => $usuario->name .
                    ' solicitó cambiar su ' .
                    $nombreCampo . '.',
                'url' => route(
                    'cambiostecnologias',
                    [
                        'solicitud' => $solicitud->id,
                    ]
                ),
                'leida' => false,
                'icono' => 'user-cog',
                'color' => 'blue',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tu solicitud de cambio fue enviada correctamente.',
            'solicitud_id' => $solicitud->id,
        ], 201);
    }

    private function datosUsuario(User $usuario): array
    {
        $usuario->loadMissing([
            'departamento.oficina.empresa',
            'numero_empleado',
        ]);

        return [
            'login' => $usuario->login,
            'name' => $usuario->name,
            'email' => $usuario->email,
            'phone' => $usuario->phone,
            'role' => $usuario->role,
            'priv_admin' => $usuario->priv_admin,
            'active' => $usuario->active,
            'mfa' => $usuario->mfa,
            'picture' => $usuario->picture
                ? asset('storage/' . $usuario->picture)
                : asset('images/user.png'),
            'empresa' => $usuario->departamento?->oficina?->empresa?->empresa,
            'departamento' => $usuario->departamento?->nombre,
            'oficina' => $usuario->departamento?->oficina?->nombre,
            'numero_empleado' =>
                $usuario->numero_empleado?->numero_empleado,
        ];
    }
}
