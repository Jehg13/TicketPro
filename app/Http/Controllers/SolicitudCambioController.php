<?php

namespace App\Http\Controllers;

use App\Models\SolicitudCambio;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitudCambioController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO DE SOLICITUDES
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | USUARIO AUTENTICADO
        |--------------------------------------------------------------------------
        */

        $usuario = Auth::user();

        $login = $usuario->login;


        /*
        |--------------------------------------------------------------------------
        | QUERY DE SOLICITUDES
        |--------------------------------------------------------------------------
        */

        $query = SolicitudCambio::with([
            'usuario.departamento.oficina',
            'revisor'
        ])->latest();


        /*
        |--------------------------------------------------------------------------
        | FILTRO POR ESTADO
        |--------------------------------------------------------------------------
        */

        if ($request->filled('estado')) {

            $query->where(
                'estado',
                $request->estado
            );
        }


        /*
        |--------------------------------------------------------------------------
        | BÚSQUEDA
        |--------------------------------------------------------------------------
        */

        if ($request->filled('buscar')) {

            $buscar = $request->buscar;

            $query->where(
                function ($q) use ($buscar) {

                    $q->where(
                        'campo',
                        'LIKE',
                        "%{$buscar}%"
                    )
                        ->orWhere(
                            'nuevo_valor',
                            'LIKE',
                            "%{$buscar}%"
                        )
                        ->orWhere(
                            'valor_actual',
                            'LIKE',
                            "%{$buscar}%"
                        )
                        ->orWhere(
                            'motivo',
                            'LIKE',
                            "%{$buscar}%"
                        )
                        ->orWhereHas(
                            'usuario',
                            function ($userQuery) use ($buscar) {

                                $userQuery
                                    ->where(
                                        'name',
                                        'LIKE',
                                        "%{$buscar}%"
                                    )
                                    ->orWhere(
                                        'email',
                                        'LIKE',
                                        "%{$buscar}%"
                                    )
                                    ->orWhere(
                                        'login',
                                        'LIKE',
                                        "%{$buscar}%"
                                    );
                            }
                        );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINACIÓN
        |--------------------------------------------------------------------------
        */

        $solicitudes =
            $query->paginate(10);


        /*
        |--------------------------------------------------------------------------
        | SOLICITUD SELECCIONADA
        |--------------------------------------------------------------------------
        */

        $seleccionada = null;


        if ($request->filled('solicitud')) {

            $seleccionada =
                SolicitudCambio::with([
                    'usuario.departamento.oficina',
                    'revisor'
                ])
                    ->find(
                        $request->solicitud
                    );
        }


        /*
        |--------------------------------------------------------------------------
        | SELECCIONAR PRIMERA SOLICITUD
        |--------------------------------------------------------------------------
        */

        if (
            $seleccionada === null &&
            $solicitudes->total() > 0
        ) {

            $primerId =
                $solicitudes
                    ->items()[0]
                    ->id;

            $seleccionada =
                SolicitudCambio::with([
                    'usuario.departamento.oficina',
                    'revisor'
                ])
                    ->find(
                        $primerId
                    );
        }


        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES - TECNOLOGÍAS
        |--------------------------------------------------------------------------
        */

        $notificaciones =
            Notificacion::where(
                'login',
                $login
            )
                ->where(
                    'tipo',
                    '!=',
                    'aviso'
                )
                ->orderByDesc(
                    'created_at'
                )
                ->limit(10)
                ->get();


        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES NO LEÍDAS
        |--------------------------------------------------------------------------
        */

        $notificacionesNoLeidas =
            Notificacion::where(
                'login',
                $login
            )
                ->where(
                    'tipo',
                    '!=',
                    'aviso'
                )
                ->where(
                    'leida',
                    false
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | ESTADÍSTICAS
        |--------------------------------------------------------------------------
        */

        $total =
            SolicitudCambio::count();

        $pendientes =
            SolicitudCambio::where(
                'estado',
                'pendiente'
            )->count();

        $aprobadas =
            SolicitudCambio::where(
                'estado',
                'aprobada'
            )->count();

        $rechazadas =
            SolicitudCambio::where(
                'estado',
                'rechazada'
            )->count();


        /*
        |--------------------------------------------------------------------------
        | VISTA
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.cambios',
            compact(

                'solicitudes',
                'seleccionada',

                'total',
                'pendientes',
                'aprobadas',
                'rechazadas',

                'notificaciones',
                'notificacionesNoLeidas'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APROBAR SOLICITUD
    |--------------------------------------------------------------------------
    */

    public function aprobar(
        Request $request,
        SolicitudCambio $solicitud
    ) {

        /*
        |--------------------------------------------------------------------------
        | VALIDAR ESTADO
        |--------------------------------------------------------------------------
        */

        if (
            $solicitud->estado !==
            'pendiente'
        ) {

            return back()->with(
                'error',
                'Esta solicitud ya fue revisada.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDAR COMENTARIO
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'comentario_admin' => [

                'nullable',
                'string',
                'max:1000'

            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR SOLICITUD
        |--------------------------------------------------------------------------
        */

        $solicitud->estado =
            'aprobada';

        $solicitud->comentario_admin =
            $request->comentario_admin;

        /*
        |----------------------------------------------------------------------
        | REVISADO POR
        |----------------------------------------------------------------------
        |
        | Si revisado_por todavía usa el ID numérico, deja Auth::id().
        |
        */

        $solicitud->revisado_por =
            Auth::id();

        $solicitud->revisado_at =
            now();

        $solicitud->save();


        /*
        |--------------------------------------------------------------------------
        | NOTIFICAR AL USUARIO
        |--------------------------------------------------------------------------
        */

        $this->notificarResultado(
            $solicitud,
            'aprobada'
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECCIÓN
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'cambiostecnologias',
                [
                    'solicitud' =>
                        $solicitud->id
                ]
            )
            ->with(
                'success',
                'La solicitud fue aprobada correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RECHAZAR SOLICITUD
    |--------------------------------------------------------------------------
    */

    public function rechazar(
        Request $request,
        SolicitudCambio $solicitud
    ) {

        /*
        |--------------------------------------------------------------------------
        | VALIDAR ESTADO
        |--------------------------------------------------------------------------
        */

        if (
            $solicitud->estado !==
            'pendiente'
        ) {

            return back()->with(
                'error',
                'Esta solicitud ya fue revisada.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDAR COMENTARIO
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'comentario_admin' => [

                'required',
                'string',
                'max:1000'

            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR SOLICITUD
        |--------------------------------------------------------------------------
        */

        $solicitud->estado =
            'rechazada';

        $solicitud->comentario_admin =
            $request->comentario_admin;

        /*
        |----------------------------------------------------------------------
        | REVISADO POR
        |----------------------------------------------------------------------
        */

        $solicitud->revisado_por =
            Auth::id();

        $solicitud->revisado_at =
            now();

        $solicitud->save();


        /*
        |--------------------------------------------------------------------------
        | NOTIFICAR AL USUARIO
        |--------------------------------------------------------------------------
        */

        $this->notificarResultado(
            $solicitud,
            'rechazada'
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECCIÓN
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'cambiostecnologias',
                [
                    'solicitud' =>
                        $solicitud->id
                ]
            )
            ->with(
                'success',
                'La solicitud fue rechazada correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | NOTIFICAR RESULTADO AL USUARIO
    |--------------------------------------------------------------------------
    */

    private function notificarResultado(
        SolicitudCambio $solicitud,
        string $resultado
    ) {

        /*
        |--------------------------------------------------------------------------
        | OBTENER LOGIN DEL USUARIO
        |--------------------------------------------------------------------------
        */

        $login =
            $solicitud->login;

        if (!$login) {

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | APROBADA
        |--------------------------------------------------------------------------
        */

        if (
            $resultado ===
            'aprobada'
        ) {

            $titulo =
                'Solicitud de cambio aprobada';

            $mensaje =
                'Tu solicitud de cambio fue aprobada por el área de Tecnologías.';

            $icono =
                'check-circle';

            $color =
                'green';
        }


        /*
        |--------------------------------------------------------------------------
        | RECHAZADA
        |--------------------------------------------------------------------------
        */

        else {

            $titulo =
                'Solicitud de cambio rechazada';

            $mensaje =
                'Tu solicitud de cambio fue rechazada por el área de Tecnologías.';

            if (
                !empty(
                    $solicitud->comentario_admin
                )
            ) {

                $mensaje .=
                    ' Motivo: ' .
                    $solicitud->comentario_admin;
            }

            $icono =
                'x-circle';

            $color =
                'red';
        }


        /*
        |--------------------------------------------------------------------------
        | CREAR NOTIFICACIÓN
        |--------------------------------------------------------------------------
        |
        | Ahora la notificación se relaciona mediante login.
        |
        */

        Notificacion::create([

            'login' =>
                $login,

            'tipo' =>
                'solicitud_cambio',

            'titulo' =>
                $titulo,

            'mensaje' =>
                $mensaje,

            'url' =>
                null,

            'leida' =>
                false,

            'icono' =>
                $icono,

            'color' =>
                $color,

        ]);
    }
}