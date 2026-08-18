<?php

namespace App\Http\Controllers;

use App\Models\TicketU;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $buscar = trim($request->input('buscar', ''));

        $tickets = TicketU::with([
            'user',
            'tomadoPor',
            'historialComentarios.usuario'
        ])
            ->where('user_id', $usuario->id)

            ->when($buscar !== '', function ($query) use ($buscar) {

                $query->where(function ($q) use ($buscar) {

                    $q->whereRaw(
                        'CAST(folio AS CHAR) LIKE ?',
                        ["%{$buscar}%"]
                    )

                    ->orWhere(
                        'titulo',
                        'LIKE',
                        "%{$buscar}%"
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | FECHA DD/MM/YYYY
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
                        | FECHA YYYY-MM-DD
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

                            // No es una fecha.
                        }
                    }
                });
            })

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

        $inicioMesActual = Carbon::now()->startOfMonth();

        $finMesActual = Carbon::now();

        $inicioMesAnterior = Carbon::now()
            ->subMonth()
            ->startOfMonth();

        $finMesAnterior = Carbon::now()
            ->subMonth()
            ->endOfMonth();


        /*
        |--------------------------------------------------------------------------
        | TICKETS
        |--------------------------------------------------------------------------
        |
        | IMPORTANTE:
        |
        | Aquí obtenemos:
        |
        | - Pendientes sin técnico
        | - Tickets tomados por el técnico actual
        |
        | Esto incluye:
        |
        | - pendiente
        | - en proceso
        | - solucionado
        | - cancelado
        |
        | siempre que estén relacionados con el técnico actual.
        |
        */

        $tickets = TicketU::with([
            'user',
            'tomadoPor',
            'historialComentarios.usuario'
        ])

            ->where(function ($query) use ($usuario) {

                /*
                |--------------------------------------------------------------------------
                | TICKETS PENDIENTES DISPONIBLES
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
                        | FOLIO
                        |--------------------------------------------------------------------------
                        */

                        $q->whereRaw(
                            'CAST(folio AS CHAR) LIKE ?',
                            ["%{$buscar}%"]
                        )

                            /*
                            |--------------------------------------------------------------------------
                            | TÍTULO
                            |--------------------------------------------------------------------------
                            */

                            ->orWhere(
                                'titulo',
                                'LIKE',
                                "%{$buscar}%"
                            );

                        /*
                        |--------------------------------------------------------------------------
                        | FECHA DD/MM/YYYY
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
                            | FECHA YYYY-MM-DD
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

                                // No es una fecha.
                            }
                        }
                    });
                }
            )


            /*
            |--------------------------------------------------------------------------
            | ORDEN
            |--------------------------------------------------------------------------
            */

            ->orderBy(
                'created_at',
                'desc'
            )


            /*
            |--------------------------------------------------------------------------
            | PAGINACIÓN
            |--------------------------------------------------------------------------
            |
            | IMPORTANTE:
            |
            | NO usamos get().
            |
            | paginate() devuelve un LengthAwarePaginator.
            |
            */

            ->paginate(10)

            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | TOTAL GENERAL
        |--------------------------------------------------------------------------
        */

        $totalTickets = TicketU::count();


        /*
        |--------------------------------------------------------------------------
        | ESTADOS GENERALES
        |--------------------------------------------------------------------------
        */

        $pendientes = TicketU::where(
            'estado',
            'pendiente'
        )->count();


        $enProceso = TicketU::where(
            'estado',
            'en proceso'
        )->count();


        $solucionados = TicketU::where(
            'estado',
            'solucionado'
        )->count();


        $cancelados = TicketU::where(
            'estado',
            'cancelado'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | MES ACTUAL
        |--------------------------------------------------------------------------
        */

        $pendientesMesActual = TicketU::where(
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


        $enProcesoMesActual = TicketU::where(
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


        $solucionadosMesActual = TicketU::where(
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


        $canceladosMesActual = TicketU::where(
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


        $totalMesActual = TicketU::whereBetween(
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

        $totalMesAnterior = TicketU::whereBetween(
            'created_at',
            [
                $inicioMesAnterior,
                $finMesAnterior
            ]
        )->count();


        $pendientesMesAnterior = TicketU::where(
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


        $enProcesoMesAnterior = TicketU::where(
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


        $solucionadosMesAnterior = TicketU::where(
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


        $canceladosMesAnterior = TicketU::where(
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
                    ($actual - $anterior)
                    / $anterior
                ) * 100,
                1
            );
        };


        $porcentajeTotal = $calcularPorcentaje(
            $totalMesActual,
            $totalMesAnterior
        );


        $porcentajePendientes = $calcularPorcentaje(
            $pendientesMesActual,
            $pendientesMesAnterior
        );


        $porcentajeEnProceso = $calcularPorcentaje(
            $enProcesoMesActual,
            $enProcesoMesAnterior
        );


        $porcentajeSolucionados = $calcularPorcentaje(
            $solucionadosMesActual,
            $solucionadosMesAnterior
        );


        $porcentajeCancelados = $calcularPorcentaje(
            $canceladosMesActual,
            $canceladosMesAnterior
        );


        /*
        |--------------------------------------------------------------------------
        | FORMATEAR PORCENTAJES
        |--------------------------------------------------------------------------
        */

        $formatearPorcentaje = function ($porcentaje) {

            if ($porcentaje > 0) {

                return '+' . $porcentaje . '%';
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
                ? $colorPorcentaje($porcentajeTotal)
                : 'text-slate-400';


        $colorPendientes =
            $pendientesMesAnterior > 0
                ? $colorPorcentaje($porcentajePendientes)
                : 'text-slate-400';


        $colorEnProceso =
            $enProcesoMesAnterior > 0
                ? $colorPorcentaje($porcentajeEnProceso)
                : 'text-slate-400';


        $colorSolucionados =
            $solucionadosMesAnterior > 0
                ? $colorPorcentaje($porcentajeSolucionados)
                : 'text-slate-400';


        $colorCancelados =
            $canceladosMesAnterior > 0
                ? $colorPorcentaje($porcentajeCancelados)
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

        $ticket = TicketU::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | YA LO TOMÓ OTRO TÉCNICO
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
        | YA LO TOMÓ ESTE TÉCNICO
        |--------------------------------------------------------------------------
        */

        if (
            !is_null($ticket->tomado_por) &&
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
                                'storage/' . $usuario->foto
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
        | NO SE PUDO TOMAR
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


            return response()->json([

                'success' => false,

                'message' =>
                    'El ticket ya no está disponible para ser tomado.'

            ], 409);
        }


        /*
        |--------------------------------------------------------------------------
        | RECARGAR
        |--------------------------------------------------------------------------
        */

        $ticket->refresh();


        /*
        |--------------------------------------------------------------------------
        | RESPUESTA
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