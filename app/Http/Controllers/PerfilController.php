<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\SolicitudCambio;
use App\Models\User;
use App\Models\Notificacion;

class PerfilController extends Controller
{
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $esAdmin = $this->esAdministradorTI($user);

        $queryNotificaciones = Notificacion::where(
            'login',
            $user->login
        );

        if ($esAdmin) {
            $queryNotificaciones->where(
                'tipo',
                '!=',
                'aviso'
            );
        }

        $notificaciones = $queryNotificaciones
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $queryNoLeidas = Notificacion::where(
            'login',
            $user->login
        )->where(
            'leida',
            false
        );

        if ($esAdmin) {
            $queryNoLeidas->where(
                'tipo',
                '!=',
                'aviso'
            );
        }

        $notificacionesNoLeidas = $queryNoLeidas->count();

        if ($esAdmin) {
            return view(
                'admin.perfil',
                compact(
                    'notificaciones',
                    'notificacionesNoLeidas'
                )
            );
        }

        return view(
            'user.perfil',
            compact(
                'notificaciones',
                'notificacionesNoLeidas'
            )
        );
    }

    private function esAdministradorTI(User $user): bool
    {
        return in_array(
            $user->role,
            [
                'Gerente Ti',
                'Soporte Tecnico',
            ],
            true
        )
            && $user->priv_admin === 'Y';
    }

    public function actualizarPassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $request->validate([
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
        ], [
            'password_actual.required' =>
                'Debes ingresar tu contraseña actual.',

            'password.required' =>
                'Debes ingresar una nueva contraseña.',

            'password.min' =>
                'La nueva contraseña debe tener al menos 8 caracteres.',

            'password.confirmed' =>
                'Las contraseñas nuevas no coinciden.',
        ]);

        $passwordGuardada = $user->pswd;

        if (!$passwordGuardada) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'No se encontró una contraseña válida para tu usuario.'
                );
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
            return back()
                ->withInput()
                ->with(
                    'error',
                    'La contraseña actual no es correcta.'
                );
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
            return back()
                ->withInput()
                ->with(
                    'error',
                    'La nueva contraseña debe ser diferente a la contraseña actual.'
                );
        }

        $user->pswd = Hash::make(
            $request->password
        );

        $user->pswd_last_updated = now();

        $user->save();

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Tu contraseña fue actualizada correctamente. Inicia sesión nuevamente con tu nueva contraseña.'
            );
    }

    public function update(Request $request)
    {
        $request->validate([
            'picture' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        if (
            $user->picture &&
            $user->picture !== 'profile-photos/user.png'
        ) {
            Storage::disk('public')->delete(
                $user->picture
            );
        }

        $path = $request
            ->file('picture')
            ->store(
                'profile-photos',
                'public'
            );

        $user->picture = $path;

        $user->save();

        return back()->with(
            'success',
            'Foto de perfil actualizada correctamente.'
        );
    }

    public function delete()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        if (
            $user->picture &&
            $user->picture !== 'profile-photos/user.png'
        ) {
            Storage::disk('public')->delete(
                $user->picture
            );
        }

        $user->picture = 'profile-photos/user.png';

        $user->save();

        return back()->with(
            'success',
            'Foto de perfil eliminada correctamente.'
        );
    }

   public function updateTecnologias(Request $request)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    if (!$user) {
        abort(401);
    }

    if (!$this->esAdministradorTI($user)) {
        abort(
            403,
            'No tienes permisos administrativos para modificar esta información.'
        );
    }

    $loginActual = $user->login;

    $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
        ],
        'login' => [
            'required',
            'string',
            'max:100',
            'unique:users,login,' . $loginActual . ',login',
        ],
        'email' => [
            'required',
            'email',
            'max:255',
            'unique:users,email,' . $loginActual . ',login',
        ],
        'phone' => [
            'nullable',
            'string',
            'max:30',
        ],
        'departamento' => [
            'required',
            'string',
            'max:255',
        ],
        'role' => [
            'required',
            'string',
            'in:Gerente Ti,Soporte Tecnico',
        ],
    ]);

    DB::beginTransaction();

    try {
        $nuevoLogin = trim($request->login);

        $user->name = trim($request->name);
        $user->login = $nuevoLogin;
        $user->email = trim($request->email);
        $user->phone = $request->filled('phone')
            ? trim($request->phone)
            : null;
        $user->role = $request->role;

        $user->save();

        if ($user->departamento) {
            $user->departamento->nombre = trim($request->departamento);
            $user->departamento->save();
        }

        if ($loginActual !== $nuevoLogin) {
            DB::table('numeros_empleado')
                ->where('login', $loginActual)
                ->update([
                    'login' => $nuevoLogin,
                ]);

            Notificacion::where('login', $loginActual)
                ->update([
                    'login' => $nuevoLogin,
                ]);
        }

        DB::commit();

        return back()->with(
            'success',
            'Información personal y laboral actualizada correctamente.'
        );
    } catch (\Throwable $e) {
        DB::rollBack();

        report($e);

        return back()->withInput()->with(
            'error',
            'No se pudo actualizar la información.'
        );
    }
}

    public function solicitarCambio(Request $request)
    {
        $request->validate([
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
        ], [
            'campo.required' =>
                'Debes seleccionar el campo que deseas modificar.',

            'campo.in' =>
                'El campo seleccionado no es válido.',

            'nuevo_valor.required' =>
                'Debes ingresar el nuevo valor.',

            'motivo.required' =>
                'Debes indicar el motivo del cambio.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        switch ($request->campo) {

            case 'nombre':
                $valorActual = $user->name;
                break;

            case 'correo':
                $valorActual = $user->email;
                break;

            case 'oficina':
                $valorActual = $user->departamento?->oficina?->nombre;
                break;

            case 'departamento':
                $valorActual = $user->departamento?->nombre;
                break;

            case 'telefono':
                $valorActual = $user->phone;
                break;

            case 'usuario':
                $valorActual = $user->login;
                break;

            case 'numeroempleado':
                $valorActual = $user->numeroempleado;
                break;

            case 'role':
                $valorActual = $user->role;
                break;

            default:
                $valorActual = null;
                break;
        }

        $solicitud = SolicitudCambio::create([
            'login' => $user->login,
            'campo' => $request->campo,
            'valor_actual' => $valorActual,
            'nuevo_valor' => $request->nuevo_valor,
            'motivo' => $request->motivo,
            'estado' => 'pendiente',
        ]);

        $administradores = User::whereIn(
            'role',
            [
                'Gerente Ti',
                'Soporte Tecnico',
            ]
        )
            ->where(
                'priv_admin',
                'Y'
            )
            ->where(
                'login',
                '!=',
                $user->login
            )
            ->get();

        $nombresCampos = [
            'nombre' =>
                'nombre completo',

            'correo' =>
                'correo electrónico',

            'oficina' =>
                'oficina / sucursal',

            'departamento' =>
                'departamento',

            'phone' =>
                'teléfono',

            'usuario' =>
                'usuario de acceso',

            'numeroempleado' =>
                'número de empleado',

            'role' =>
                'rol',
        ];

        $nombreCampo =
            $nombresCampos[$request->campo]
            ?? $request->campo;

        foreach ($administradores as $administrador) {

    Notificacion::create([
        'login' =>
            $administrador->login,

        'tipo' =>
            'solicitud_cambio',

        'titulo' =>
            'Nueva solicitud de cambio',

        'mensaje' =>
            $user->name .
            ' solicitó cambiar su ' .
            $nombreCampo .
            '.',

        'url' =>
            route(
                'cambiostecnologias',
                [
                    'solicitud' =>
                        $solicitud->id,
                ]
            ),

        'leida' =>
            false,

        'icono' =>
            'user-cog',

        'color' =>
            'blue',
    ]);
}

        return back()->with(
            'success',
            'Tu solicitud de cambio fue enviada correctamente.'
        );
    }
}
