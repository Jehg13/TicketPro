<?php

namespace App\Http\Controllers;

use App\Models\TicketU;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MisticketsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | TICKETS DEL USUARIO
    |--------------------------------------------------------------------------
    */

    public function create(Request $request)
    {
        $usuario = Auth::user();

        $buscar = trim(
            $request->input('buscar', '')
        );

        $tickets = TicketU::with([
            'user',
            'historialComentarios.usuario'
        ])
        ->where(
            'user_id',
            $usuario->id
        )
        ->when(
            $buscar !== '',
            function ($query) use ($buscar) {

                $query->where(function ($q) use ($buscar) {

                    /*
                    |--------------------------------------------------------------------------
                    | BUSCAR FOLIO
                    |--------------------------------------------------------------------------
                    */

                    $q->whereRaw(
                        'CAST(folio AS CHAR) LIKE ?',
                        ["%{$buscar}%"]
                    )

                    /*
                    |--------------------------------------------------------------------------
                    | BUSCAR TITULO
                    |--------------------------------------------------------------------------
                    */

                    ->orWhere(
                        'titulo',
                        'LIKE',
                        "%{$buscar}%"
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | BUSCAR FECHA DD/MM/YYYY
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

                        /*
                        |--------------------------------------------------------------------------
                        | BUSCAR FECHA YYYY-MM-DD
                        |--------------------------------------------------------------------------
                        */

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
                });
            }
        )
        ->orderBy(
            'created_at',
            'desc'
        )
        ->paginate(5)
        ->withQueryString();

        return view(
            'user.mistickets',
            compact(
                'tickets',
                'buscar'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TICKETS PARA TECNOLOGÍA
    |--------------------------------------------------------------------------
    */

    public function tecnologias(Request $request)
    {
        $usuario = Auth::user();

        $buscar = trim(
            $request->input('buscar', '')
        );


        /*
        |--------------------------------------------------------------------------
        | FECHAS
        |--------------------------------------------------------------------------
        */

        $inicioMesActual =
            Carbon::now()->startOfMonth();

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
        | TICKETS
        |--------------------------------------------------------------------------
        |
        | Se muestran:
        |
        | 1. Tickets pendientes que todavía no tienen técnico.
        |
        | 2. Tickets que fueron tomados por el técnico actualmente
        |    autenticado.
        |
        */

        $tickets = TicketU::with([
            'user',
            'historialComentarios.usuario'
        ])
        ->where(function ($query) use ($usuario) {

            /*
            |--------------------------------------------------------------------------
            | TICKETS DISPONIBLES
            |--------------------------------------------------------------------------
            */

            $query->where(function ($q) {

                $q->where(
                    'estado',
                    'pendiente'
                )
                ->whereNull(
                    'tomado_por'
                );

            })

            /*
            |--------------------------------------------------------------------------
            | TICKETS DEL TÉCNICO ACTUAL
            |--------------------------------------------------------------------------
            */

            ->orWhere(
                'tomado_por',
                $usuario->id
            );
        })

        /*
        |--------------------------------------------------------------------------
        | BÚSQUEDA
        |--------------------------------------------------------------------------
        */

        ->when(
            $buscar !== '',
            function ($query) use ($buscar) {

                $query->where(function ($q) use ($buscar) {

                    /*
                    |--------------------------------------------------------------------------
                    | BUSCAR FOLIO
                    |--------------------------------------------------------------------------
                    */

                    $q->whereRaw(
                        'CAST(folio AS CHAR) LIKE ?',
                        ["%{$buscar}%"]
                    )

                    /*
                    |--------------------------------------------------------------------------
                    | BUSCAR TITULO
                    |--------------------------------------------------------------------------
                    */

                    ->orWhere(
                        'titulo',
                        'LIKE',
                        "%{$buscar}%"
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | BUSCAR FECHA DD/MM/YYYY
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

                        /*
                        |--------------------------------------------------------------------------
                        | BUSCAR FECHA YYYY-MM-DD
                        |--------------------------------------------------------------------------
                        */

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
                });
            }
        )

        ->orderBy(
            'created_at',
            'desc'
        )

        ->paginate(10)

        ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | TOTAL DE TICKETS
        |--------------------------------------------------------------------------
        */

        $totalTickets =
            TicketU::count();


        /*
        |--------------------------------------------------------------------------
        | ESTADOS
        |--------------------------------------------------------------------------
        */

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
        | TOTALES MES ACTUAL
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
        | TOTALES MES ANTERIOR
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
        | CALCULAR PORCENTAJES
        |--------------------------------------------------------------------------
        */

        $calcularPorcentaje =
            function ($actual, $anterior) {

                if ($anterior == 0) {

                    return $actual > 0
                        ? 100
                        : 0;
                }

                return round(
                    (
                        ($actual - $anterior)
                        / $anterior
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
        | FORMATEAR PORCENTAJES
        |--------------------------------------------------------------------------
        */

        $formatearPorcentaje =
            function ($porcentaje) {

                if ($porcentaje > 0) {

                    return '+' .
                        $porcentaje .
                        '%';
                }

                return $porcentaje . '%';
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

        $colorPorcentaje =
            function ($porcentaje) {

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
        | VISTA
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.ticket',
            compact(

                'tickets',
                'buscar',

                'totalTickets',
                'pendientes',
                'enProceso',
                'solucionados',
                'cancelados',

                'totalMesActual',
                'pendientesMesActual',
                'enProcesoMesActual',
                'solucionadosMesActual',
                'canceladosMesActual',

                'totalMesAnterior',
                'pendientesMesAnterior',
                'enProcesoMesAnterior',
                'solucionadosMesAnterior',
                'canceladosMesAnterior',

                'porcentajeTotal',
                'porcentajePendientes',
                'porcentajeEnProceso',
                'porcentajeSolucionados',
                'porcentajeCancelados',

                'porcentajeTotalTexto',
                'porcentajePendientesTexto',
                'porcentajeEnProcesoTexto',
                'porcentajeSolucionadosTexto',
                'porcentajeCanceladosTexto',

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


        /*
        |--------------------------------------------------------------------------
        | BUSCAR TICKET
        |--------------------------------------------------------------------------
        */

        $ticket = TicketU::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | EVITAR QUE OTRO TÉCNICO TOME EL TICKET
        |--------------------------------------------------------------------------
        */

        if (
            !is_null($ticket->tomado_por) &&
            (int) $ticket->tomado_por !== (int) $usuario->id
        ) {

            return response()->json([
                'success' => false,
                'message' =>
                    'Este ticket ya fue tomado por otro técnico.'
            ], 409);
        }


        /*
        |--------------------------------------------------------------------------
        | SI EL MISMO TÉCNICO YA LO TOMÓ
        |--------------------------------------------------------------------------
        |
        | No generamos una nueva fecha.
        |
        */

        if (
            (int) $ticket->tomado_por === (int) $usuario->id
        ) {

            return response()->json([

                'success' => true,

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
        | TOMAR TICKET DE FORMA SEGURA
        |--------------------------------------------------------------------------
        |
        | Actualizamos únicamente si tomado_por continúa siendo NULL.
        |
        | Esto evita que dos técnicos puedan tomar el mismo ticket
        | al mismo tiempo.
        |
        */

        $fechaTomado = now();


        $actualizado = TicketU::where(
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
        | OTRO TÉCNICO LO TOMÓ ANTES
        |--------------------------------------------------------------------------
        */

        if ($actualizado === 0) {

            $ticket->refresh();


            if (
                !is_null($ticket->tomado_por) &&
                (int) $ticket->tomado_por !== (int) $usuario->id
            ) {

                return response()->json([

                    'success' => false,

                    'message' =>
                        'Este ticket ya fue tomado por otro técnico.'

                ], 409);
            }


            /*
            |--------------------------------------------------------------------------
            | ESTADO CAMBIÓ POR OTRA OPERACIÓN
            |--------------------------------------------------------------------------
            */

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
        | RESPUESTA AJAX
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

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