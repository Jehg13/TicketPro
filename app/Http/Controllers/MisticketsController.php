<?php

namespace App\Http\Controllers;

use App\Models\TicketU;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MisticketsController extends Controller
{
    public function create(Request $request)
    {
        $usuario = Auth::user();
        $login = $usuario->login;

        $buscar = trim($request->input('buscar', ''));

        $tickets = TicketU::with([
            'user',
            'tomadoPor',
            'historialComentarios.usuario'
        ])
            ->where('login', $login)
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
                        }
                    }
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->withQueryString();

        $notificaciones = Notificacion::where(
            'login',
            $login
        )
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $notificacionesNoLeidas = Notificacion::where(
            'login',
            $login
        )
            ->where('leida', false)
            ->count();

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

    public function tecnologias(Request $request)
    {
        $usuario = Auth::user();
        $login = trim((string) $usuario->login);

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

        $notificaciones = Notificacion::where(
            'login',
            $login
        )
            ->where(
                'tipo',
                '!=',
                'aviso'
            )
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $notificacionesNoLeidas = Notificacion::where(
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

        $inicioMesActual = Carbon::now()->startOfMonth();
        $finMesActual = Carbon::now();

        $inicioMesAnterior = Carbon::now()
            ->subMonth()
            ->startOfMonth();

        $finMesAnterior = Carbon::now()
            ->subMonth()
            ->endOfMonth();

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

        if ($filtro === 'mis tickets') {

            $ticketsQuery->whereNotNull('tomado_por');

            $ticketsQuery->whereRaw(
                'LOWER(TRIM(CAST(tomado_por AS CHAR))) = LOWER(?)',
                [$login]
            );
        }

        if ($filtro === 'pendiente') {
            $ticketsQuery->where(
                'estado',
                'pendiente'
            );
        }

        if ($filtro === 'en proceso') {
            $ticketsQuery->where(
                'estado',
                'en proceso'
            );
        }

        if ($filtro === 'solucionado') {
            $ticketsQuery->where(
                'estado',
                'solucionado'
            );
        }

        if ($filtro === 'cancelado') {
            $ticketsQuery->where(
                'estado',
                'cancelado'
            );
        }

        if ($buscar !== '') {

            $ticketsQuery->where(function ($q) use ($buscar) {

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
                    }
                }
            });
        }

        /*
        |----------------------------------------------------------------------
        | DEPURACIÓN
        |----------------------------------------------------------------------
        */

        if ($request->boolean('debug')) {

            $debugQuery = clone $ticketsQuery;

            $ticketsDebug = $debugQuery
                ->orderBy(
                    'created_at',
                    'desc'
                )
                ->get([
                    'id',
                    'folio',
                    'login',
                    'tomado_por',
                    'estado',
                    'titulo',
                    'created_at'
                ]);

            $ticketsTomados = TicketU::whereNotNull(
                'tomado_por'
            )
                ->get([
                    'id',
                    'folio',
                    'login',
                    'tomado_por',
                    'estado',
                    'titulo'
                ]);

            dd([

                'usuario' => [
                    'id' => $usuario->id,
                    'name' => $usuario->name,
                    'login' => $usuario->login,
                    'login_trim' => $login,
                    'login_tipo' => gettype($usuario->login),
                ],

                'filtro_recibido' => $request->input('filtro'),

                'filtro_normalizado' => $filtro,

                'buscar' => $buscar,

                'sql' => $debugQuery->toSql(),

                'bindings' => $debugQuery->getBindings(),

                'tickets_que_deberian_aparecer' =>
                    $ticketsDebug->toArray(),

                'cantidad_tickets_filtrados' =>
                    $ticketsDebug->count(),

                'todos_los_tickets_tomados' =>
                    $ticketsTomados->toArray(),

                'comparacion' => TicketU::whereNotNull(
                    'tomado_por'
                )
                    ->get([
                        'id',
                        'folio',
                        'tomado_por'
                    ])
                    ->map(function ($ticket) use ($login) {

                        return [
                            'id' =>
                                $ticket->id,

                            'folio' =>
                                $ticket->folio,

                            'tomado_por_db' =>
                                $ticket->tomado_por,

                            'tomado_por_tipo' =>
                                gettype(
                                    $ticket->tomado_por
                                ),

                            'login_usuario' =>
                                $login,

                            'login_usuario_tipo' =>
                                gettype($login),

                            'igual_directo' =>
                                $ticket->tomado_por === $login,

                            'igual_string' =>
                                (string) $ticket->tomado_por ===
                                (string) $login,

                            'igual_sin_espacios' =>
                                trim(
                                    (string) $ticket->tomado_por
                                ) === trim(
                                    (string) $login
                                ),

                            'lower_igual' =>
                                strtolower(
                                    trim(
                                        (string) $ticket->tomado_por
                                    )
                                ) === strtolower(
                                    trim(
                                        (string) $login
                                    )
                                ),
                        ];
                    })
                    ->toArray()
            ]);
        }

        $tickets = $ticketsQuery
            ->orderBy(
                'created_at',
                'desc'
            )
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {

            return response()->json([

                'success' => true,

                'filtro' => $filtro,

                'buscar' => $buscar,

                'tickets' => $tickets->items(),

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

        $totalTickets = TicketU::count();

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

        $calcularPorcentaje = function (
            $actual,
            $anterior
        ) {

            if ($anterior == 0) {
                return $actual > 0 ? 100 : 0;
            }

            return round(
                (
                    ($actual - $anterior) /
                    $anterior
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

        $formatearPorcentaje = function ($porcentaje) {

            return $porcentaje > 0
                ? '+' . $porcentaje . '%'
                : $porcentaje . '%';
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

        return view(
            'admin.ticket',
            compact(
                'tickets',
                'buscar',
                'filtro',
                'notificaciones',
                'notificacionesNoLeidas',
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

    public function tomar($id)
    {
        $usuario = Auth::user();
        $login = trim((string) $usuario->login);

        $ticket = TicketU::findOrFail($id);

        if (
            !is_null($ticket->tomado_por) &&
            trim((string) $ticket->tomado_por) !== $login
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Este ticket ya fue tomado por otro técnico.'
            ], 409);
        }

        if (
            !is_null($ticket->tomado_por) &&
            trim((string) $ticket->tomado_por) === $login
        ) {
            return response()->json([
                'success' => true,
                'message' =>
                    'Este ticket ya está asignado a ti.',
                'ticket_id' =>
                    $ticket->id,
                'tomado_por' => [
                    'login' =>
                        $usuario->login,
                    'name' =>
                        $usuario->name,
                    'foto' =>
                        $usuario->foto
                            ? asset(
                                'storage/' .
                                $usuario->foto
                            )
                            : asset(
                                'images/user.png'
                            ),
                ],
                'fecha_tomado' =>
                    $ticket->fecha_tomado,
                'estado' =>
                    $ticket->estado,
            ]);
        }

        $fechaTomado = now();

        $actualizado = TicketU::where(
            'id',
            $ticket->id
        )
            ->whereNull('tomado_por')
            ->where(
                'estado',
                'pendiente'
            )
            ->update([
                'tomado_por' =>
                    $login,
                'fecha_tomado' =>
                    $fechaTomado,
                'estado' =>
                    'en proceso',
                'updated_at' =>
                    now(),
            ]);

        if ($actualizado === 0) {

            $ticket->refresh();

            if (
                !is_null($ticket->tomado_por) &&
                trim((string) $ticket->tomado_por) !== $login
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

        $ticket->refresh();

        Notificacion::create([
            'login' =>
                $ticket->login,
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
            'icono' =>
                'ticket',
            'color' =>
                'green',
        ]);

        return response()->json([
            'success' => true,
            'message' =>
                'Ticket tomado correctamente.',
            'ticket_id' =>
                $ticket->id,
            'tomado_por' => [
                'login' =>
                    $usuario->login,
                'name' =>
                    $usuario->name,
                'foto' =>
                    $usuario->foto
                        ? asset(
                            'storage/' .
                            $usuario->foto
                        )
                        : asset(
                            'images/user.png'
                        ),
            ],
            'fecha_tomado' =>
                $ticket->fecha_tomado,
            'estado' =>
                $ticket->estado,
        ]);
    }
}