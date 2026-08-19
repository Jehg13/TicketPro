<?php

namespace App\Http\Controllers;

use App\Models\TicketU;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MisticketsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MIS TICKETS - USUARIO
    |--------------------------------------------------------------------------
    */

    public function create(Request $request)
    {
        $usuario = Auth::user();

        $buscar = trim(
            $request->input('buscar', '')
        );

        /*
        |--------------------------------------------------------------------------
        | TICKETS DEL USUARIO
        |--------------------------------------------------------------------------
        */

        $tickets = TicketU::with([
            'user',
            'tomadoPor',
            'historialComentarios.usuario'
        ])
            ->where(
                'user_id',
                $usuario->id
            )
            ->when(
                $buscar !== '',
                function ($query) use ($buscar) {

                    $query->where(
                        function ($q) use ($buscar) {

                            $q->whereRaw(
                                'CAST(folio AS CHAR) LIKE ?',
                                ["%{$buscar}%"]
                            )
                                ->orWhere(
                                    'titulo',
                                    'LIKE',
                                    "%{$buscar}%"
                                )
                                ->orWhere(
                                    'tipo_falla',
                                    'LIKE',
                                    "%{$buscar}%"
                                )
                                ->orWhere(
                                    'prioridad',
                                    'LIKE',
                                    "%{$buscar}%"
                                )
                                ->orWhere(
                                    'descripcion',
                                    'LIKE',
                                    "%{$buscar}%"
                                );

                            /*
                            |--------------------------------------------------------------------------
                            | BUSCAR POR FECHA
                            |--------------------------------------------------------------------------
                            */

                            try {

                                $fecha = Carbon::createFromFormat(
                                    'd/m/Y',
                                    $buscar
                                )->format('Y-m-d');

                                $q->orWhereDate(
                                    'created_at',
                                    $fecha
                                );

                            } catch (\Exception $e) {

                                try {

                                    $fecha = Carbon::createFromFormat(
                                        'Y-m-d',
                                        $buscar
                                    )->format('Y-m-d');

                                    $q->orWhereDate(
                                        'created_at',
                                        $fecha
                                    );

                                } catch (\Exception $e) {
                                    // No es una fecha válida.
                                }
                            }
                        }
                    );
                }
            )
            ->orderBy(
                'created_at',
                'desc'
            )
            ->paginate(5)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES DEL USUARIO
        |--------------------------------------------------------------------------
        */

        $notificaciones = Notificacion::where(
            'user_id',
            $usuario->id
        )
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES NO LEÍDAS
        |--------------------------------------------------------------------------
        */

        $notificacionesNoLeidas = Notificacion::where(
            'user_id',
            $usuario->id
        )
            ->where(
                'leida',
                false
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | VISTA
        |--------------------------------------------------------------------------
        */

        return view(
            'user.mistickets',
            compact(
                'tickets',
                'buscar',
                'notificaciones',
                'notificacionesNoLeidas'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TICKETS - TECNOLOGÍAS
    |--------------------------------------------------------------------------
    */

    public function tecnologias(Request $request)
    {
        $usuario = Auth::user();

        $buscar = trim(
            $request->input('buscar', '')
        );

        $filtro = strtolower(
            trim(
                $request->input(
                    'filtro',
                    'todos'
                )
            )
        );


        /*
        |--------------------------------------------------------------------------
        | FILTROS PERMITIDOS
        |--------------------------------------------------------------------------
        */

        $filtrosPermitidos = [
            'todos',
            'mis tickets',
            'pendiente',
            'en proceso',
            'solucionado',
            'cancelado'
        ];

        if (!in_array(
            $filtro,
            $filtrosPermitidos,
            true
        )) {

            $filtro = 'todos';
        }


        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES - TECNOLOGÍAS
        |--------------------------------------------------------------------------
        |
        | IMPORTANTE:
        |
        | Los usuarios de tecnologías NO deben ver los avisos generales.
        |
        | Por eso excluimos:
        |
        | tipo = aviso
        |
        | De esta manera únicamente aparecerán notificaciones relacionadas
        | con tickets u otros eventos propios del área de tecnologías.
        |
        |--------------------------------------------------------------------------
        */

        $notificaciones = Notificacion::where(
            'user_id',
            $usuario->id
        )
            ->where(
                'tipo',
                '!=',
                'aviso'
            )
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES NO LEÍDAS - TECNOLOGÍAS
        |--------------------------------------------------------------------------
        |
        | También excluimos los avisos del contador.
        |
        | Así, si existen 3 avisos y 1 notificación de ticket:
        |
        | El contador mostrará solamente:
        |
        | 1
        |
        |--------------------------------------------------------------------------
        */

        $notificacionesNoLeidas = Notificacion::where(
            'user_id',
            $usuario->id
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
        | FECHAS
        |--------------------------------------------------------------------------
        */

        $inicioMesActual =
            Carbon::now()
                ->startOfMonth();

        $finMesActual =
            Carbon::now();

        $inicioMesAnterior =
            Carbon::now()
                ->subMonth()
                ->startOfMonth();

        $finMesAnterior =
            Carbon::now()
                ->subMonth()
                ->endOfMonth();


        /*
        |--------------------------------------------------------------------------
        | QUERY DE TICKETS
        |--------------------------------------------------------------------------
        */

        $ticketsQuery = TicketU::with([
            'user',
            'user.departamento',
            'user.departamento.oficina',
            'user.departamento.oficina.empresa',
            'tomadoPor',
            'tomadoPor.departamento',
            'historialComentarios.usuario',
            'solucion'
        ]);


        /*
        |--------------------------------------------------------------------------
        | FILTROS
        |--------------------------------------------------------------------------
        */

        switch ($filtro) {

            case 'mis tickets':

                $ticketsQuery->where(
                    'tomado_por',
                    $usuario->id
                );

                break;


            case 'pendiente':

                $ticketsQuery->where(
                    'estado',
                    'pendiente'
                );

                break;


            case 'en proceso':

                $ticketsQuery->where(
                    'estado',
                    'en proceso'
                );

                break;


            case 'solucionado':

                $ticketsQuery->where(
                    'estado',
                    'solucionado'
                );

                break;


            case 'cancelado':

                $ticketsQuery->where(
                    'estado',
                    'cancelado'
                );

                break;
        }


        /*
        |--------------------------------------------------------------------------
        | BÚSQUEDA
        |--------------------------------------------------------------------------
        */

        if ($buscar !== '') {

            $ticketsQuery->where(
                function ($q) use ($buscar) {

                    $q->whereRaw(
                        'CAST(folio AS CHAR) LIKE ?',
                        ["%{$buscar}%"]
                    )
                        ->orWhere(
                            'titulo',
                            'LIKE',
                            "%{$buscar}%"
                        )
                        ->orWhere(
                            'tipo_falla',
                            'LIKE',
                            "%{$buscar}%"
                        )
                        ->orWhere(
                            'prioridad',
                            'LIKE',
                            "%{$buscar}%"
                        )
                        ->orWhere(
                            'descripcion',
                            'LIKE',
                            "%{$buscar}%"
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | BUSCAR POR FECHA
                    |--------------------------------------------------------------------------
                    */

                    try {

                        $fecha = Carbon::createFromFormat(
                            'd/m/Y',
                            $buscar
                        )->format('Y-m-d');

                        $q->orWhereDate(
                            'created_at',
                            $fecha
                        );

                    } catch (\Exception $e) {

                        try {

                            $fecha = Carbon::createFromFormat(
                                'Y-m-d',
                                $buscar
                            )->format('Y-m-d');

                            $q->orWhereDate(
                                'created_at',
                                $fecha
                            );

                        } catch (\Exception $e) {
                            // No es una fecha válida.
                        }
                    }
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINACIÓN
        |--------------------------------------------------------------------------
        */

        $tickets = $ticketsQuery
            ->orderBy(
                'created_at',
                'desc'
            )
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | RESPUESTA AJAX
        |--------------------------------------------------------------------------
        */

        if ($request->ajax()) {

            return response()->json([

                'success' =>
                    true,

                'filtro' =>
                    $filtro,

                'buscar' =>
                    $buscar,

                'tickets' =>
                    $tickets->items(),

                'pagination' => [

                    'current_page' =>
                        $tickets->currentPage(),

                    'last_page' =>
                        $tickets->lastPage(),

                    'per_page' =>
                        $tickets->perPage(),

                    'total' =>
                        $tickets->total(),

                    'from' =>
                        $tickets->firstItem(),

                    'to' =>
                        $tickets->lastItem(),
                ],
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | ESTADÍSTICAS
        |--------------------------------------------------------------------------
        */

        $totalTickets =
            TicketU::count();

        $pendientes =
            TicketU::where(
                'estado',
                'pendiente'
            )->count();

        $enProceso =
            TicketU::where(
                'estado',
                'en proceso'
            )->count();

        $solucionados =
            TicketU::where(
                'estado',
                'solucionado'
            )->count();

        $cancelados =
            TicketU::where(
                'estado',
                'cancelado'
            )->count();


        /*
        |--------------------------------------------------------------------------
        | MES ACTUAL
        |--------------------------------------------------------------------------
        */

        $pendientesMesActual =
            TicketU::where(
                'estado',
                'pendiente'
            )
                ->whereBetween(
                    'created_at',
                    [
                        $inicioMesActual,
                        $finMesActual
                    ]
                )
                ->count();

        $enProcesoMesActual =
            TicketU::where(
                'estado',
                'en proceso'
            )
                ->whereBetween(
                    'created_at',
                    [
                        $inicioMesActual,
                        $finMesActual
                    ]
                )
                ->count();

        $solucionadosMesActual =
            TicketU::where(
                'estado',
                'solucionado'
            )
                ->whereBetween(
                    'created_at',
                    [
                        $inicioMesActual,
                        $finMesActual
                    ]
                )
                ->count();

        $canceladosMesActual =
            TicketU::where(
                'estado',
                'cancelado'
            )
                ->whereBetween(
                    'created_at',
                    [
                        $inicioMesActual,
                        $finMesActual
                    ]
                )
                ->count();

        $totalMesActual =
            TicketU::whereBetween(
                'created_at',
                [
                    $inicioMesActual,
                    $finMesActual
                ]
            )->count();


        /*
        |--------------------------------------------------------------------------
        | MES ANTERIOR
        |--------------------------------------------------------------------------
        */

        $totalMesAnterior =
            TicketU::whereBetween(
                'created_at',
                [
                    $inicioMesAnterior,
                    $finMesAnterior
                ]
            )->count();

        $pendientesMesAnterior =
            TicketU::where(
                'estado',
                'pendiente'
            )
                ->whereBetween(
                    'created_at',
                    [
                        $inicioMesAnterior,
                        $finMesAnterior
                    ]
                )
                ->count();

        $enProcesoMesAnterior =
            TicketU::where(
                'estado',
                'en proceso'
            )
                ->whereBetween(
                    'created_at',
                    [
                        $inicioMesAnterior,
                        $finMesAnterior
                    ]
                )
                ->count();

        $solucionadosMesAnterior =
            TicketU::where(
                'estado',
                'solucionado'
            )
                ->whereBetween(
                    'created_at',
                    [
                        $inicioMesAnterior,
                        $finMesAnterior
                    ]
                )
                ->count();

        $canceladosMesAnterior =
            TicketU::where(
                'estado',
                'cancelado'
            )
                ->whereBetween(
                    'created_at',
                    [
                        $inicioMesAnterior,
                        $finMesAnterior
                    ]
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | PORCENTAJES
        |--------------------------------------------------------------------------
        */

        $calcularPorcentaje = function (
            $actual,
            $anterior
        ) {

            if ($anterior == 0) {

                return $actual > 0
                    ? 100
                    : 0;
            }

            return round(
                (
                    (
                        $actual -
                        $anterior
                    )
                    /
                    $anterior
                ) * 100,
                1
            );
        };


        $porcentajeTotal =
            $calcularPorcentaje(
                $totalMesActual,
                $totalMesAnterior
            );

        $porcentajePendientes =
            $calcularPorcentaje(
                $pendientesMesActual,
                $pendientesMesAnterior
            );

        $porcentajeEnProceso =
            $calcularPorcentaje(
                $enProcesoMesActual,
                $enProcesoMesAnterior
            );

        $porcentajeSolucionados =
            $calcularPorcentaje(
                $solucionadosMesActual,
                $solucionadosMesAnterior
            );

        $porcentajeCancelados =
            $calcularPorcentaje(
                $canceladosMesActual,
                $canceladosMesAnterior
            );


        /*
        |--------------------------------------------------------------------------
        | TEXTO PORCENTAJES
        |--------------------------------------------------------------------------
        */

        $formatearPorcentaje = function ($porcentaje) {

            if ($porcentaje > 0) {

                return '+' .
                    $porcentaje .
                    '%';
            }

            return $porcentaje .
                '%';
        };


        $porcentajeTotalTexto =
            $totalMesAnterior > 0
                ? $formatearPorcentaje(
                    $porcentajeTotal
                ) . ' vs mes pasado'
                : 'Este mes';

        $porcentajePendientesTexto =
            $pendientesMesAnterior > 0
                ? $formatearPorcentaje(
                    $porcentajePendientes
                ) . ' vs mes pasado'
                : 'Este mes';

        $porcentajeEnProcesoTexto =
            $enProcesoMesAnterior > 0
                ? $formatearPorcentaje(
                    $porcentajeEnProceso
                ) . ' vs mes pasado'
                : 'Este mes';

        $porcentajeSolucionadosTexto =
            $solucionadosMesAnterior > 0
                ? $formatearPorcentaje(
                    $porcentajeSolucionados
                ) . ' vs mes pasado'
                : 'Este mes';

        $porcentajeCanceladosTexto =
            $canceladosMesAnterior > 0
                ? $formatearPorcentaje(
                    $porcentajeCancelados
                ) . ' vs mes pasado'
                : 'Este mes';


        /*
        |--------------------------------------------------------------------------
        | COLORES
        |--------------------------------------------------------------------------
        */

        $colorPorcentaje = function ($porcentaje) {

            if ($porcentaje > 0) {

                return 'text-emerald-400';
            }

            if ($porcentaje < 0) {

                return 'text-rose-400';
            }

            return 'text-slate-400';
        };


        $colorTotal =
            $totalMesAnterior > 0
                ? $colorPorcentaje(
                    $porcentajeTotal
                )
                : 'text-slate-400';

        $colorPendientes =
            $pendientesMesAnterior > 0
                ? $colorPorcentaje(
                    $porcentajePendientes
                )
                : 'text-slate-400';

        $colorEnProceso =
            $enProcesoMesAnterior > 0
                ? $colorPorcentaje(
                    $porcentajeEnProceso
                )
                : 'text-slate-400';

        $colorSolucionados =
            $solucionadosMesAnterior > 0
                ? $colorPorcentaje(
                    $porcentajeSolucionados
                )
                : 'text-slate-400';

        $colorCancelados =
            $canceladosMesAnterior > 0
                ? $colorPorcentaje(
                    $porcentajeCancelados
                )
                : 'text-slate-400';


        /*
        |--------------------------------------------------------------------------
        | VISTA TECNOLOGÍAS
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.ticket',
            compact(

                'tickets',
                'buscar',
                'filtro',

                /*
                |------------------------------------------------------------------
                | NOTIFICACIONES
                |------------------------------------------------------------------
                */

                'notificaciones',
                'notificacionesNoLeidas',

                /*
                |------------------------------------------------------------------
                | ESTADÍSTICAS
                |------------------------------------------------------------------
                */

                'totalTickets',
                'pendientes',
                'enProceso',
                'solucionados',
                'cancelados',

                /*
                |------------------------------------------------------------------
                | MES ACTUAL
                |------------------------------------------------------------------
                */

                'totalMesActual',
                'pendientesMesActual',
                'enProcesoMesActual',
                'solucionadosMesActual',
                'canceladosMesActual',

                /*
                |------------------------------------------------------------------
                | MES ANTERIOR
                |------------------------------------------------------------------
                */

                'totalMesAnterior',
                'pendientesMesAnterior',
                'enProcesoMesAnterior',
                'solucionadosMesAnterior',
                'canceladosMesAnterior',

                /*
                |------------------------------------------------------------------
                | PORCENTAJES
                |------------------------------------------------------------------
                */

                'porcentajeTotal',
                'porcentajePendientes',
                'porcentajeEnProceso',
                'porcentajeSolucionados',
                'porcentajeCancelados',

                /*
                |------------------------------------------------------------------
                | TEXTOS
                |------------------------------------------------------------------
                */

                'porcentajeTotalTexto',
                'porcentajePendientesTexto',
                'porcentajeEnProcesoTexto',
                'porcentajeSolucionadosTexto',
                'porcentajeCanceladosTexto',

                /*
                |------------------------------------------------------------------
                | COLORES
                |------------------------------------------------------------------
                */

                'colorTotal',
                'colorPendientes',
                'colorEnProceso',
                'colorSolucionados',
                'colorCancelados'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TOMAR TICKET
    |--------------------------------------------------------------------------
    */

    public function tomar($id)
    {
        $usuario = Auth::user();

        $ticket = TicketU::findOrFail(
            $id
        );


        /*
        |--------------------------------------------------------------------------
        | YA FUE TOMADO POR OTRO TÉCNICO
        |--------------------------------------------------------------------------
        */

        if (
            !is_null($ticket->tomado_por)
            &&
            (int) $ticket->tomado_por !==
            (int) $usuario->id
        ) {

            return response()->json([
                'success' => false,

                'message' =>
                    'Este ticket ya fue tomado por otro técnico.'
            ], 409);
        }


        /*
        |--------------------------------------------------------------------------
        | YA LO TIENE ESTE TÉCNICO
        |--------------------------------------------------------------------------
        */

        if (
            !is_null($ticket->tomado_por)
            &&
            (int) $ticket->tomado_por ===
            (int) $usuario->id
        ) {

            return response()->json([

                'success' =>
                    true,

                'message' =>
                    'Este ticket ya está asignado a ti.',

                'ticket_id' =>
                    $ticket->id,

                'tomado_por' => [

                    'id' =>
                        $usuario->id,

                    'name' =>
                        $usuario->name,

                    'foto' =>
                        $usuario->foto
                            ? asset(
                                'storage/' .
                                $usuario->foto
                            )
                            : null,
                ],

                'fecha_tomado' =>
                    $ticket->fecha_tomado,

                'estado' =>
                    $ticket->estado,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | TOMAR TICKET
        |--------------------------------------------------------------------------
        */

        $fechaTomado = now();

        $actualizado =
            TicketU::where(
                'id',
                $ticket->id
            )
                ->whereNull(
                    'tomado_por'
                )
                ->where(
                    'estado',
                    'pendiente'
                )
                ->update([

                    'tomado_por' =>
                        $usuario->id,

                    'fecha_tomado' =>
                        $fechaTomado,

                    'estado' =>
                        'en proceso',

                    'updated_at' =>
                        now(),
                ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDAR ACTUALIZACIÓN
        |--------------------------------------------------------------------------
        */

        if ($actualizado === 0) {

            $ticket->refresh();

            if (
                !is_null($ticket->tomado_por)
                &&
                (int) $ticket->tomado_por !==
                (int) $usuario->id
            ) {

                return response()->json([
                    'success' => false,

                    'message' =>
                        'Este ticket ya fue tomado por otro técnico.'
                ], 409);
            }

            return response()->json([
                'success' => false,

                'message' =>
                    'El ticket ya no está disponible para ser tomado.'
            ], 409);
        }


        /*
        |--------------------------------------------------------------------------
        | RECARGAR TICKET
        |--------------------------------------------------------------------------
        */

        $ticket->refresh();


        /*
        |--------------------------------------------------------------------------
        | CREAR NOTIFICACIÓN PARA EL USUARIO
        |--------------------------------------------------------------------------
        |
        | Esta notificación SÍ se muestra al usuario propietario del ticket.
        |
        |--------------------------------------------------------------------------
        */

        Notificacion::create([

            'user_id' =>
                $ticket->user_id,

            'tipo' =>
                'ticket',

            'titulo' =>
                'Ticket tomado',

            'mensaje' =>
                "El técnico {$usuario->name} ha tomado tu ticket #{$ticket->folio}: {$ticket->titulo}",

            'url' =>
                route(
                    'ticketusuario.detalles',
                    $ticket->id
                ),

            'leida' =>
                false,
        ]);


        /*
        |--------------------------------------------------------------------------
        | RESPUESTA
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' =>
                true,

            'message' =>
                'Ticket tomado correctamente.',

            'ticket_id' =>
                $ticket->id,

            'tomado_por' => [

                'id' =>
                    $usuario->id,

                'name' =>
                    $usuario->name,

                'foto' =>
                    $usuario->foto
                        ? asset(
                            'storage/' .
                            $usuario->foto
                        )
                        : null,
            ],

            'fecha_tomado' =>
                $ticket->fecha_tomado,

            'estado' =>
                $ticket->estado,
        ]);
    }
}