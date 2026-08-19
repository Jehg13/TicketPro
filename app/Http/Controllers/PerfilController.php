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
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES
        |--------------------------------------------------------------------------
        |
        | Tecnologías:
        |   - NO recibe avisos
        |   - SÍ recibe solicitudes de cambio
        |
        | Usuarios normales:
        |   - Pueden recibir avisos y demás notificaciones
        |
        */

        $queryNotificaciones = Notificacion::where(
            'user_id',
            $user->id
        );

        /*
        |--------------------------------------------------------------------------
        | TECNOLOGÍAS NO DEBE VER NOTIFICACIONES DE AVISOS
        |--------------------------------------------------------------------------
        */

        if ($user->rol === 'tecnologias') {

            $queryNotificaciones->where(
                'tipo',
                '!=',
                'aviso'
            );
        }

        $notificaciones =
            $queryNotificaciones
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();


        /*
        |--------------------------------------------------------------------------
        | CONTADOR DE NOTIFICACIONES NO LEÍDAS
        |--------------------------------------------------------------------------
        */

        $queryNoLeidas = Notificacion::where(
            'user_id',
            $user->id
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

        if ($user->rol === 'tecnologias') {

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
        | VISTA
        |--------------------------------------------------------------------------
        */

        if ($user->rol === 'tecnologias') {

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

    public function update(
        Request $request
    ) {

        $request->validate([
            'foto' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ]);


        /** @var \App\Models\User $user */
        $user = auth()->user();


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

        $path =
            $request
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
        $user = auth()->user();


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
        | COLOCAR FOTO POR DEFECTO
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

    public function updateTecnologias(
        Request $request
    ) {

        /** @var \App\Models\User $user */
        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | SOLO TECNOLOGÍAS
        |--------------------------------------------------------------------------
        */

        if (
            $user->rol !== 'tecnologias'
        ) {

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

    public function solicitarCambio(
        Request $request
    ) {

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
        | USUARIO ACTUAL
        |--------------------------------------------------------------------------
        */

        /** @var \App\Models\User $user */
        $user = Auth::user();


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
        */

        $solicitud =
            SolicitudCambio::create([

                'user_id' =>
                    $user->id,

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
        |
        | Las solicitudes de cambio SÍ se notifican a Tecnologías.
        |
        | No se envían a:
        | - El usuario que realizó la solicitud.
        |
        */

        $tecnologias =
            User::where(
                'rol',
                'tecnologias'
            )
            ->where(
                'id',
                '!=',
                $user->id
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
        | CREAR NOTIFICACIÓN PARA TECNOLOGÍAS
        |--------------------------------------------------------------------------
        */

        foreach (
            $tecnologias
            as $tecnico
        ) {

            /*
            |--------------------------------------------------------------------------
            | ASEGURAR QUE SOLO SEA UNA NOTIFICACIÓN
            | DE SOLICITUD DE CAMBIO
            |--------------------------------------------------------------------------
            */

            Notificacion::create([

                'user_id' =>
                    $tecnico->id,

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