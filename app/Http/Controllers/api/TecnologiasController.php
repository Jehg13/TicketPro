<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\TicketU;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TecnologiasController extends Controller
{
    private string $timezone = 'America/Matamoros';

    public function index(Request $request): JsonResponse
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.'
            ], 401);
        }

        [$fechaInicio, $fechaFin] = $this->obtenerRangoFiltro($request);

        $rangoActivo = $fechaInicio !== null && $fechaFin !== null;

        $inicioFiltroUTC = $rangoActivo
            ? $fechaInicio->copy()->startOfDay()->utc()
            : null;

        $finFiltroUTC = $rangoActivo
            ? $fechaFin->copy()->endOfDay()->utc()
            : null;

        $baseQuery = function () use ($rangoActivo, $inicioFiltroUTC, $finFiltroUTC) {
            $query = TicketU::query();

            if ($rangoActivo) {
                $query->whereBetween('ticket_u_s.created_at', [
                    $inicioFiltroUTC,
                    $finFiltroUTC
                ]);
            }

            return $query;
        };

        $ahora = Carbon::now($this->timezone);

        $totalTickets = $baseQuery()->count();

        $ticketsPendientes = $baseQuery()
            ->where('estado', 'pendiente')
            ->count();

        $ticketsResueltos = $baseQuery()
            ->where('estado', 'solucionado')
            ->count();

        $ticketsAbiertos = $baseQuery()
            ->where('estado', 'en proceso')
            ->count();

        if ($rangoActivo) {
            $diasPeriodo = $fechaInicio->copy()
                ->startOfDay()
                ->diffInDays($fechaFin->copy()->startOfDay()) + 1;

            $inicioComparacion = $fechaInicio->copy()
                ->subDays($diasPeriodo)
                ->startOfDay();

            $finComparacion = $fechaInicio->copy()
                ->subDay()
                ->endOfDay();

            $ticketsMes = $totalTickets;

            $ticketsMesAnterior = TicketU::whereBetween('ticket_u_s.created_at', [
                $inicioComparacion->copy()->utc(),
                $finComparacion->copy()->utc()
            ])->count();

            $ticketsSemana = $totalTickets;
            $ticketsSemanaAnterior = $ticketsMesAnterior;

            $inicioSemanaActual = $fechaInicio->copy()->startOfDay();
            $finSemanaActual = $fechaFin->copy()->endOfDay();

            $inicioSemanaAnterior = $inicioComparacion->copy();
            $finSemanaAnterior = $finComparacion->copy();
        } else {
            $inicioComparacion = null;
            $finComparacion = null;

            $inicioMesActual = $ahora->copy()->startOfMonth();
            $finMesActual = $ahora->copy()->endOfMonth();

            $ticketsMes = TicketU::whereBetween('ticket_u_s.created_at', [
                $inicioMesActual->copy()->utc(),
                $finMesActual->copy()->utc()
            ])->count();

            $inicioMesAnterior = $ahora->copy()->subMonth()->startOfMonth();
            $finMesAnterior = $ahora->copy()->subMonth()->endOfMonth();

            $ticketsMesAnterior = TicketU::whereBetween('ticket_u_s.created_at', [
                $inicioMesAnterior->copy()->utc(),
                $finMesAnterior->copy()->utc()
            ])->count();

            $inicioSemanaActual = $ahora->copy()->startOfWeek();
            $finSemanaActual = $ahora->copy()->endOfWeek();

            $ticketsSemana = TicketU::whereBetween('ticket_u_s.created_at', [
                $inicioSemanaActual->copy()->utc(),
                $finSemanaActual->copy()->utc()
            ])->count();

            $inicioSemanaAnterior = $ahora->copy()->subWeek()->startOfWeek();
            $finSemanaAnterior = $ahora->copy()->subWeek()->endOfWeek();

            $ticketsSemanaAnterior = TicketU::whereBetween('ticket_u_s.created_at', [
                $inicioSemanaAnterior->copy()->utc(),
                $finSemanaAnterior->copy()->utc()
            ])->count();
        }

        if ($ticketsMesAnterior > 0) {
            $porcentajeMes = round(
                (($ticketsMes - $ticketsMesAnterior) / $ticketsMesAnterior) * 100
            );

            $textoMes = ($porcentajeMes >= 0 ? '+' : '') . $porcentajeMes . '%';
            $subtextoMes = $rangoActivo ? 'vs periodo anterior' : 'vs mes pasado';
        } else {
            $porcentajeMes = null;
            $textoMes = $rangoActivo ? 'Periodo seleccionado' : 'Este mes';
            $subtextoMes = '';
        }

        if ($ticketsSemanaAnterior > 0) {
            $porcentajeSemana = round(
                (($ticketsSemana - $ticketsSemanaAnterior) / $ticketsSemanaAnterior) * 100
            );

            $textoSemana = ($porcentajeSemana >= 0 ? '+' : '') . $porcentajeSemana . '%';
            $subtextoSemana = $rangoActivo ? 'vs periodo anterior' : 'vs semana pasada';
        } else {
            $porcentajeSemana = null;
            $textoSemana = $rangoActivo ? 'Periodo seleccionado' : 'Esta semana';
            $subtextoSemana = '';
        }

        $ticketsAtendidosSemanaActual = TicketU::whereNotNull('fecha_tomado')
            ->whereHas('solucion')
            ->with('solucion')
            ->when(
                $rangoActivo,
                function ($query) use ($inicioFiltroUTC, $finFiltroUTC) {
                    $query->whereBetween('ticket_u_s.fecha_tomado', [
                        $inicioFiltroUTC,
                        $finFiltroUTC
                    ]);
                },
                function ($query) use ($inicioSemanaActual, $finSemanaActual) {
                    $query->whereBetween('ticket_u_s.fecha_tomado', [
                        $inicioSemanaActual->copy()->utc(),
                        $finSemanaActual->copy()->utc()
                    ]);
                }
            )
            ->get();

        $tiemposSemanaActual = $ticketsAtendidosSemanaActual
            ->map(function ($ticket) {
                if (!$ticket->solucion || !$ticket->solucion->fecha_solucion) {
                    return null;
                }

                return Carbon::parse($ticket->fecha_tomado)
                    ->diffInSeconds(Carbon::parse($ticket->solucion->fecha_solucion));
            })
            ->filter();

        $promedioSemanaActual = $tiemposSemanaActual->count() > 0
            ? $tiemposSemanaActual->avg()
            : null;

        $ticketsAtendidosSemanaAnterior = TicketU::whereNotNull('fecha_tomado')
            ->whereHas('solucion')
            ->with('solucion')
            ->when(
                $rangoActivo,
                function ($query) use ($inicioComparacion, $finComparacion) {
                    $query->whereBetween('ticket_u_s.fecha_tomado', [
                        $inicioComparacion->copy()->utc(),
                        $finComparacion->copy()->utc()
                    ]);
                },
                function ($query) use ($inicioSemanaAnterior, $finSemanaAnterior) {
                    $query->whereBetween('ticket_u_s.fecha_tomado', [
                        $inicioSemanaAnterior->copy()->utc(),
                        $finSemanaAnterior->copy()->utc()
                    ]);
                }
            )
            ->get();

        $tiemposSemanaAnterior = $ticketsAtendidosSemanaAnterior
            ->map(function ($ticket) {
                if (!$ticket->solucion || !$ticket->solucion->fecha_solucion) {
                    return null;
                }

                return Carbon::parse($ticket->fecha_tomado)
                    ->diffInSeconds(Carbon::parse($ticket->solucion->fecha_solucion));
            })
            ->filter();

        $promedioSemanaAnterior = $tiemposSemanaAnterior->count() > 0
            ? $tiemposSemanaAnterior->avg()
            : null;

        if ($promedioSemanaActual !== null) {
            $horas = floor($promedioSemanaActual / 3600);
            $minutos = floor(($promedioSemanaActual % 3600) / 60);
            $tiempoPromedio = $horas . 'h ' . $minutos . 'm';
        } else {
            $tiempoPromedio = 'Sin datos';
        }

        if (
            $promedioSemanaAnterior !== null &&
            $promedioSemanaActual !== null &&
            $promedioSemanaAnterior > 0
        ) {
            $porcentajeTiempo = round(
                (($promedioSemanaActual - $promedioSemanaAnterior) / $promedioSemanaAnterior) * 100
            );

            $textoTiempo = ($porcentajeTiempo >= 0 ? '+' : '') . $porcentajeTiempo . '%';
            $subtextoTiempo = $rangoActivo ? 'vs periodo anterior' : 'vs semana pasada';
        } else {
            $porcentajeTiempo = null;
            $textoTiempo = $rangoActivo ? 'Periodo seleccionado' : 'Esta semana';
            $subtextoTiempo = '';
        }

        $quejasRecurrentes = $baseQuery()
            ->select('tipo_falla')
            ->selectRaw('COUNT(*) as total')
            ->whereNotNull('tipo_falla')
            ->where('tipo_falla', '!=', '')
            ->groupBy('tipo_falla')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $maxQuejas = $quejasRecurrentes->max('total') ?? 0;

        $quejasRecurrentes = $quejasRecurrentes
            ->map(function ($queja) use ($maxQuejas) {
                return [
                    'tipo_falla' => $queja->tipo_falla,
                    'total' => (int) $queja->total,
                    'porcentaje' => $maxQuejas > 0
                        ? round(($queja->total / $maxQuejas) * 100, 1)
                        : 0
                ];
            })
            ->values();

        $equipos = $baseQuery()
            ->select('equipo')
            ->selectRaw('COUNT(*) as fallas')
            ->selectRaw('MIN(ticket_u_s.created_at) as primera_incidencia')
            ->selectRaw('MAX(ticket_u_s.created_at) as ultima_incidencia')
            ->whereNotNull('equipo')
            ->where('equipo', '!=', '')
            ->groupBy('equipo')
            ->orderByDesc('fallas')
            ->orderBy('primera_incidencia')
            ->limit(5)
            ->get();

        $equipos = $equipos
            ->map(function ($equipo) {
                $nombre = strtoupper(trim($equipo->equipo));

                if (str_starts_with($nombre, 'PC-')) {
                    $tipo = 'Desktop';
                    $icono = 'monitor';
                } elseif (str_starts_with($nombre, 'LAP-')) {
                    $tipo = 'Laptop';
                    $icono = 'laptop';
                } elseif (str_starts_with($nombre, 'IMP-')) {
                    $tipo = 'Impresora';
                    $icono = 'printer';
                } else {
                    $tipo = 'Equipo';
                    $icono = 'monitor';
                }

                return [
                    'equipo' => $equipo->equipo,
                    'fallas' => (int) $equipo->fallas,
                    'primera_incidencia' => $equipo->primera_incidencia
                        ? Carbon::parse($equipo->primera_incidencia)
                            ->setTimezone($this->timezone)
                            ->toIso8601String()
                        : null,
                    'ultima_incidencia' => $equipo->ultima_incidencia
                        ? Carbon::parse($equipo->ultima_incidencia)
                            ->setTimezone($this->timezone)
                            ->toIso8601String()
                        : null,
                    'tipo' => $tipo,
                    'icono' => $icono
                ];
            })
            ->values();

        $equipoMayorRecurrencia = $equipos->first();

        $ubicaciones = $baseQuery()
            ->join('users', 'ticket_u_s.login', '=', 'users.login')
            ->join('departamentos', 'users.login', '=', 'departamentos.usuario_departamento')
            ->join('oficinas', 'departamentos.oficina_id', '=', 'oficinas.id')
            ->select('oficinas.id', 'oficinas.nombre')
            ->selectRaw('COUNT(ticket_u_s.id) as total')
            ->groupBy('oficinas.id', 'oficinas.nombre')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $maxUbicaciones = $ubicaciones->max('total') ?? 0;

        $ubicaciones = $ubicaciones
            ->map(function ($ubicacion) use ($maxUbicaciones) {
                return [
                    'id' => (int) $ubicacion->id,
                    'nombre' => $ubicacion->nombre,
                    'total' => (int) $ubicacion->total,
                    'porcentaje' => $maxUbicaciones > 0
                        ? round(($ubicacion->total / $maxUbicaciones) * 100, 1)
                        : 0
                ];
            })
            ->values();

        $datosEvolucion = $this->obtenerEvolucion(
            $request->input('periodo', 'semana'),
            $fechaInicio,
            $fechaFin
        );

        $notificaciones = Notificacion::where('login', $usuario->login)
            ->where('tipo', '!=', 'aviso')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(function ($notificacion) {
                return $notificacion->toArray();
            })
            ->values();

        $notificacionesNoLeidas = Notificacion::where('login', $usuario->login)
            ->where('tipo', '!=', 'aviso')
            ->where('leida', false)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'usuario' => [
                    'login' => $usuario->login,
                    'name' => $usuario->name ?? null,
                    'email' => $usuario->email ?? null
                ],
                'notificaciones' => $notificaciones,
                'notificacionesNoLeidas' => $notificacionesNoLeidas,
                'totalTickets' => $totalTickets,
                'ticketsPendientes' => $ticketsPendientes,
                'ticketsResueltos' => $ticketsResueltos,
                'ticketsAbiertos' => $ticketsAbiertos,
                'ticketsMes' => $ticketsMes,
                'textoMes' => $textoMes,
                'subtextoMes' => $subtextoMes,
                'porcentajeMes' => $porcentajeMes,
                'ticketsSemana' => $ticketsSemana,
                'textoSemana' => $textoSemana,
                'subtextoSemana' => $subtextoSemana,
                'porcentajeSemana' => $porcentajeSemana,
                'tiempoPromedio' => $tiempoPromedio,
                'textoTiempo' => $textoTiempo,
                'subtextoTiempo' => $subtextoTiempo,
                'porcentajeTiempo' => $porcentajeTiempo,
                'quejasRecurrentes' => $quejasRecurrentes,
                'equipos' => $equipos,
                'equipoMayorRecurrencia' => $equipoMayorRecurrencia,
                'ubicaciones' => $ubicaciones,
                'evolucionTickets' => $datosEvolucion['datos'],
                'promedioEvolucion' => $datosEvolucion['promedio'],
                'maximoEvolucion' => $datosEvolucion['maximo'],
                'minimoEvolucion' => $datosEvolucion['minimo'],
                'periodo' => $request->input('periodo', 'semana'),
                'fechaInicio' => $fechaInicio?->toDateString(),
                'fechaFin' => $fechaFin?->toDateString()
            ]
        ]);
    }

    public function evolucion(Request $request): JsonResponse
    {
        $periodo = $request->input('periodo', 'semana');

        if (!in_array($periodo, ['hoy', 'semana', 'mes', 'año'], true)) {
            $periodo = 'semana';
        }

        [$fechaInicio, $fechaFin] = $this->obtenerRangoFiltro($request);

        $datosEvolucion = $this->obtenerEvolucion(
            $periodo,
            $fechaInicio,
            $fechaFin
        );

        return response()->json([
            'success' => true,
            'data' => [
                'periodo' => $periodo,
                'evolucionTickets' => $datosEvolucion['datos'],
                'promedioEvolucion' => $datosEvolucion['promedio'],
                'maximoEvolucion' => $datosEvolucion['maximo'],
                'minimoEvolucion' => $datosEvolucion['minimo']
            ]
        ]);
    }

    private function obtenerRangoFiltro(Request $request): array
    {
        $fechaInicioInput = $request->input('fecha_inicio');
        $fechaFinInput = $request->input('fecha_fin');

        if (!$fechaInicioInput && !$fechaFinInput) {
            return [null, null];
        }

        try {
            $fechaInicio = $fechaInicioInput
                ? Carbon::createFromFormat(
                    'Y-m-d',
                    $fechaInicioInput,
                    $this->timezone
                )->startOfDay()
                : null;

            $fechaFin = $fechaFinInput
                ? Carbon::createFromFormat(
                    'Y-m-d',
                    $fechaFinInput,
                    $this->timezone
                )->endOfDay()
                : null;

            if (!$fechaInicio && $fechaFin) {
                $fechaInicio = $fechaFin->copy()->startOfDay();
            }

            if ($fechaInicio && !$fechaFin) {
                $fechaFin = $fechaInicio->copy()->endOfDay();
            }

            if ($fechaInicio && $fechaFin && $fechaInicio->greaterThan($fechaFin)) {
                [$fechaInicio, $fechaFin] = [$fechaFin, $fechaInicio];

                $fechaInicio->startOfDay();
                $fechaFin->endOfDay();
            }

            return [$fechaInicio, $fechaFin];
        } catch (\Throwable $e) {
            return [null, null];
        }
    }

    private function obtenerEvolucion(
        string $periodo,
        ?Carbon $fechaInicio = null,
        ?Carbon $fechaFin = null
    ): array {
        $ahora = Carbon::now($this->timezone);
        $filtroActivo = $fechaInicio !== null && $fechaFin !== null;

        if ($filtroActivo) {
            $inicio = $fechaInicio->copy()->startOfDay();
            $fin = $fechaFin->copy()->endOfDay();

            $tickets = TicketU::whereBetween('ticket_u_s.created_at', [
                $inicio->copy()->utc(),
                $fin->copy()->utc()
            ])->get(['ticket_u_s.created_at']);

            if ($periodo === 'hoy') {
                $datos = $this->agruparPorHora($tickets, $inicio, $fin);
            } elseif ($periodo === 'mes') {
                $datos = $this->agruparPorDia($tickets, $inicio, $fin);
            } elseif ($periodo === 'año') {
                $datos = $this->agruparPorMes($tickets, $inicio, $fin);
            } else {
                $datos = $this->agruparPorDia($tickets, $inicio, $fin);
            }

            return $this->calcularMetricas($datos);
        }

        if ($periodo === 'hoy') {
            $inicio = $ahora->copy()->startOfDay();
            $fin = $ahora->copy()->endOfHour();

            $tickets = TicketU::whereBetween('ticket_u_s.created_at', [
                $inicio->copy()->utc(),
                $fin->copy()->utc()
            ])->get(['ticket_u_s.created_at']);

            $datos = $this->agruparPorHora($tickets, $inicio, $fin);
        } elseif ($periodo === 'semana') {
            $inicio = $ahora->copy()->startOfDay()->subDays(6);
            $fin = $ahora->copy()->endOfDay();

            $tickets = TicketU::whereBetween('ticket_u_s.created_at', [
                $inicio->copy()->utc(),
                $fin->copy()->utc()
            ])->get(['ticket_u_s.created_at']);

            $datos = $this->agruparPorDia($tickets, $inicio, $fin);
        } elseif ($periodo === 'mes') {
            $inicio = $ahora->copy()->startOfMonth();
            $fin = $ahora->copy()->endOfMonth();

            $tickets = TicketU::whereBetween('ticket_u_s.created_at', [
                $inicio->copy()->utc(),
                $fin->copy()->utc()
            ])->get(['ticket_u_s.created_at']);

            $datos = $this->agruparPorDia($tickets, $inicio, $fin);
        } else {
            $inicio = $ahora->copy()->startOfYear();
            $fin = $ahora->copy()->endOfYear();

            $tickets = TicketU::whereBetween('ticket_u_s.created_at', [
                $inicio->copy()->utc(),
                $fin->copy()->utc()
            ])->get(['ticket_u_s.created_at']);

            $datos = $this->agruparPorMes($tickets, $inicio, $fin);
        }

        return $this->calcularMetricas($datos);
    }

    private function agruparPorHora($tickets, Carbon $inicio, Carbon $fin): array
    {
        $totales = [];

        foreach ($tickets as $ticket) {
            $fecha = Carbon::parse($ticket->created_at)
                ->setTimezone($this->timezone);

            $clave = $fecha->format('Y-m-d H');
            $totales[$clave] = ($totales[$clave] ?? 0) + 1;
        }

        $datos = [];
        $cursor = $inicio->copy()->startOfHour();
        $limite = $fin->copy()->startOfHour();

        while ($cursor->lessThanOrEqualTo($limite)) {
            $clave = $cursor->format('Y-m-d H');

            $datos[] = [
                'fecha' => $cursor->format('H:00'),
                'fecha_completa' => $cursor->format('Y-m-d H:i'),
                'total' => $totales[$clave] ?? 0
            ];

            $cursor->addHour();
        }

        return $datos;
    }

    private function agruparPorDia($tickets, Carbon $inicio, Carbon $fin): array
    {
        $totales = [];

        foreach ($tickets as $ticket) {
            $fecha = Carbon::parse($ticket->created_at)
                ->setTimezone($this->timezone);

            $clave = $fecha->format('Y-m-d');
            $totales[$clave] = ($totales[$clave] ?? 0) + 1;
        }

        $datos = [];
        $cursor = $inicio->copy()->startOfDay();
        $limite = $fin->copy()->startOfDay();

        while ($cursor->lessThanOrEqualTo($limite)) {
            $clave = $cursor->format('Y-m-d');

            $datos[] = [
                'fecha' => $cursor->locale('es')->translatedFormat('d M'),
                'fecha_completa' => $clave,
                'total' => $totales[$clave] ?? 0
            ];

            $cursor->addDay();
        }

        return $datos;
    }

    private function agruparPorMes($tickets, Carbon $inicio, Carbon $fin): array
    {
        $totales = [];

        foreach ($tickets as $ticket) {
            $fecha = Carbon::parse($ticket->created_at)
                ->setTimezone($this->timezone);

            $clave = $fecha->format('Y-m');
            $totales[$clave] = ($totales[$clave] ?? 0) + 1;
        }

        $datos = [];
        $cursor = $inicio->copy()->startOfMonth();
        $limite = $fin->copy()->startOfMonth();

        while ($cursor->lessThanOrEqualTo($limite)) {
            $clave = $cursor->format('Y-m');

            $datos[] = [
                'fecha' => $cursor->locale('es')->translatedFormat('M'),
                'fecha_completa' => $clave,
                'total' => $totales[$clave] ?? 0
            ];

            $cursor->addMonth();
        }

        return $datos;
    }

    private function calcularMetricas(array $datos): array
    {
        $valores = collect($datos)
            ->pluck('total')
            ->map(fn ($valor) => (int) $valor);

        return [
            'datos' => array_values($datos),
            'promedio' => $valores->count() > 0 ? round($valores->avg(), 1) : 0,
            'maximo' => $valores->max() ?? 0,
            'minimo' => $valores->min() ?? 0
        ];
    }
}
