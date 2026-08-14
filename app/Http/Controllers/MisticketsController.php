<?php

namespace App\Http\Controllers;

use App\Models\TicketU;
use App\Models\TicketComentario;
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
                            // No es una fecha válida
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
        | IMPORTANTE:
        | Aquí agregamos:
        |
        | historialComentarios.usuario
        |
        | para que los comentarios y el usuario estén disponibles
        | dentro del modal.
        |
        */

        $tickets = TicketU::with([
            'historialComentarios.usuario'
        ])
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
                    | TITULO
                    |--------------------------------------------------------------------------
                    */

                    ->orWhere(
                        'titulo',
                        'LIKE',
                        "%{$buscar}%"
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | FECHA
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
                            // No es una fecha válida
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
        | TOTALES
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

        $totalMesActual =
            TicketU::whereBetween(
                'created_at',
                [
                    $inicioMesActual,
                    $finMesActual
                ]
            )->count();


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
}