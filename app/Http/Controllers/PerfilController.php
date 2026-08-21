<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\SolicitudCambio;
use App\Models\User;
use App\Models\Notificacion;

class PerfilController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MOSTRAR PERFIL
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES
        |--------------------------------------------------------------------------
        |
        | Ahora se relacionan mediante:
        |
        | notificaciones.login = users.login
        |
        */

        $queryNotificaciones = Notificacion::where(
            'login',
            $user->login
        );

        /*
        |--------------------------------------------------------------------------
        | TECNOLOGÍAS NO VE AVISOS
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'tecnologias') {

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


        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES NO LEÍDAS
        |--------------------------------------------------------------------------
        */

        $queryNoLeidas = Notificacion::where(
            'login',
            $user->login
        )
            ->where(
                'leida',
                false
            );

        /*
        |--------------------------------------------------------------------------
        | TECNOLOGÍAS NO CUENTA AVISOS
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'tecnologias') {

            $queryNoLeidas->where(
                'tipo',
                '!=',
                'aviso'
            );
        }

        $notificacionesNoLeidas =
            $queryNoLeidas->count();


        /*
        |--------------------------------------------------------------------------
        | MOSTRAR VISTA SEGÚN ROL
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'tecnologias') {

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


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR FOTO DE PERFIL
    |--------------------------------------------------------------------------
    */

    public function update(Request $request)
    {
        $request->validate([

            'foto' => [
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


        /*
        |--------------------------------------------------------------------------
        | ELIMINAR FOTO ANTERIOR
        |--------------------------------------------------------------------------
        */

        if (
            $user->foto &&
            $user->foto !== 'profile-photos/user.png'
        ) {

            Storage::disk('public')->delete(
                $user->foto
            );
        }


        /*
        |--------------------------------------------------------------------------
        | GUARDAR NUEVA FOTO
        |--------------------------------------------------------------------------
        */

        $path = $request
            ->file('foto')
            ->store(
                'profile-photos',
                'public'
            );

        $user->foto = $path;

        $user->save();


        return back()->with(
            'success',
            'Foto de perfil actualizada correctamente.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR FOTO DE PERFIL
    |--------------------------------------------------------------------------
    */

    public function delete()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }


        /*
        |--------------------------------------------------------------------------
        | ELIMINAR FOTO PERSONAL
        |--------------------------------------------------------------------------
        */

        if (
            $user->foto &&
            $user->foto !== 'profile-photos/user.png'
        ) {

            Storage::disk('public')->delete(
                $user->foto
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FOTO POR DEFECTO
        |--------------------------------------------------------------------------
        */

        $user->foto =
            'profile-photos/user.png';

        $user->save();


        return back()->with(
            'success',
            'Foto de perfil eliminada correctamente.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR PERFIL DE TECNOLOGÍAS
    |--------------------------------------------------------------------------
    */

    public function updateTecnologias(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }


        /*
        |--------------------------------------------------------------------------
        | SOLO TECNOLOGÍAS
        |--------------------------------------------------------------------------
        */

        if ($user->role !== 'tecnologias') {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],

            'departamento' => [
                'required',
                'string',
                'max:255',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR USUARIO
        |--------------------------------------------------------------------------
        */

        $user->name =
            $request->name;

        $user->email =
            $request->email;


        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR DEPARTAMENTO
        |--------------------------------------------------------------------------
        */

        if ($user->departamento) {

            $user->departamento->nombre =
                $request->departamento;

            $user->departamento->save();
        }


        $user->save();


        return back()->with(
            'success',
            'Información personal actualizada correctamente.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SOLICITAR CAMBIO DE PERFIL
    |--------------------------------------------------------------------------
    */

    public function solicitarCambio(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'campo' => [
                'required',
                'string',
                'in:nombre,correo,oficina,departamento,ubicacion',
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


        /*
        |--------------------------------------------------------------------------
        | USUARIO AUTENTICADO
        |--------------------------------------------------------------------------
        */

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }


        /*
        |--------------------------------------------------------------------------
        | OBTENER VALOR ACTUAL
        |--------------------------------------------------------------------------
        */

        switch ($request->campo) {

            case 'nombre':

                $valorActual =
                    $user->name;

                break;


            case 'correo':

                $valorActual =
                    $user->email;

                break;


            case 'departamento':

                $valorActual =
                    $user->departamento
                        ? $user->departamento->nombre
                        : null;

                break;


            case 'oficina':

                $valorActual =
                    $user->departamento &&
                    $user->departamento->oficina

                        ? $user
                            ->departamento
                            ->oficina
                            ->nombre

                        : null;

                break;


            case 'ubicacion':

                $valorActual =
                    $user->ubicacion;

                break;


            default:

                $valorActual = null;

                break;
        }


        /*
        |--------------------------------------------------------------------------
        | CREAR SOLICITUD
        |--------------------------------------------------------------------------
        |
        | solicitud_cambios.login = users.login
        |
        */

        $solicitud = SolicitudCambio::create([

            'login' =>
                $user->login,

            'campo' =>
                $request->campo,

            'valor_actual' =>
                $valorActual,

            'nuevo_valor' =>
                $request->nuevo_valor,

            'motivo' =>
                $request->motivo,

            'estado' =>
                'pendiente',

        ]);


        /*
        |--------------------------------------------------------------------------
        | OBTENER USUARIOS DE TECNOLOGÍAS
        |--------------------------------------------------------------------------
        */

        $tecnologias = User::where(
            'role',
            'tecnologias'
        )
            ->where(
                'login',
                '!=',
                $user->login
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | NOMBRE DEL CAMPO
        |--------------------------------------------------------------------------
        */

        $nombresCampos = [

            'nombre' =>
                'nombre',

            'correo' =>
                'correo electrónico',

            'oficina' =>
                'oficina',

            'departamento' =>
                'departamento',

            'ubicacion' =>
                'ubicación',

        ];

        $nombreCampo =
            $nombresCampos[
                $request->campo
            ] ?? $request->campo;


        /*
        |--------------------------------------------------------------------------
        | CREAR NOTIFICACIONES PARA TECNOLOGÍAS
        |--------------------------------------------------------------------------
        */

        foreach ($tecnologias as $tecnico) {

            Notificacion::create([

                /*
                |--------------------------------------------------------------------------
                | DESTINATARIO
                |--------------------------------------------------------------------------
                |
                | notificaciones.login = users.login
                |
                */

                'login' =>
                    $tecnico->login,

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
                                $solicitud->id
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


        /*
        |--------------------------------------------------------------------------
        | RESPUESTA
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            'Tu solicitud de cambio fue enviada correctamente.'
        );
    }
}