<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TicketU;
use App\Models\Aviso;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        Log::info('==============================================');
        Log::info('🎫 API TICKETS - INICIO');
        Log::info('==============================================');

        try {
            $usuario = Auth::user();

            Log::info('👤 Usuario autenticado:', [
                'usuario' => $usuario
                    ? $usuario->toArray()
                    : null,
            ]);

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado',
                ], 401);
            }

            Log::info('🔎 Buscando tickets:', [
                'login' => $usuario->login,
            ]);

            $tickets = TicketU::where(
                'login',
                $usuario->login
            )
                ->with([
                    'user.departamento.oficina',
                    'tomadoPor',
                    'solucion',
                    'solucion.solucionadoPor',
                ])
                ->orderByDesc('id')
                ->get();

            Log::info('📊 Cantidad de tickets:', [
                'cantidad' => $tickets->count(),
            ]);

            $total = $tickets->count();

            $abiertos = $tickets
                ->filter(function ($ticket) {
                    return strtolower(
                        trim($ticket->estado ?? '')
                    ) === 'abierto';
                })
                ->count();

            $enProceso = $tickets
                ->filter(function ($ticket) {
                    return strtolower(
                        trim($ticket->estado ?? '')
                    ) === 'en proceso';
                })
                ->count();

            $solucionados = $tickets
                ->filter(function ($ticket) {
                    return strtolower(
                        trim($ticket->estado ?? '')
                    ) === 'solucionado';
                })
                ->count();

            $cancelados = $tickets
                ->filter(function ($ticket) {
                    return strtolower(
                        trim($ticket->estado ?? '')
                    ) === 'cancelado';
                })
                ->count();

            $ultimo = $tickets->first();

            $recientes = $tickets
                ->take(5)
                ->map(function ($ticket) use ($usuario) {
                    return $this->formatearTicket(
                        $ticket,
                        $usuario
                    );
                })
                ->values();

            $ultimoTicket = $ultimo
                ? $this->formatearTicket(
                    $ultimo,
                    $usuario
                )
                : null;

            $actividad = $this->generarActividad(
                $tickets
            );

            $avisos = Aviso::orderByDesc(
                'created_at'
            )
                ->limit(3)
                ->get()
                ->map(function ($aviso) {
                    return [
                        'id' => $aviso->id,
                        'titulo' => $aviso->titulo,
                        'descripcion' => $aviso->descripcion,
                        'created_at' => $aviso->created_at,
                        'updated_at' => $aviso->updated_at,
                    ];
                })
                ->values();

            $notificaciones = Notificacion::where(
                'login',
                $usuario->login
            )
                ->latest('created_at')
                ->limit(10)
                ->get()
                ->map(function ($notificacion) {
                    return [
                        'id' => $notificacion->id,
                        'login' => $notificacion->login,
                        'titulo' => $notificacion->titulo,
                        'mensaje' => $notificacion->mensaje,
                        'tipo' => $notificacion->tipo,
                        'leida' => $notificacion->leida,
                        'created_at' => $notificacion->created_at,
                        'updated_at' => $notificacion->updated_at,
                    ];
                })
                ->values();

            $notificacionesNoLeidas = Notificacion::where(
                'login',
                $usuario->login
            )
                ->where(
                    'leida',
                    false
                )
                ->count();

            $respuesta = [
                'success' => true,

                'resumen' => [
                    'total' => $total,
                    'abiertos' => $abiertos,
                    'en_proceso' => $enProceso,
                    'solucionados' => $solucionados,
                    'cancelados' => $cancelados,
                ],

                'ultimo_ticket' => $ultimoTicket,

                'tickets_recientes' => $recientes,

                'actividad' => $actividad,

                'avisos' => $avisos,

                'notificaciones' => $notificaciones,

                'notificaciones_no_leidas' =>
                    $notificacionesNoLeidas,
            ];

            Log::info('📤 RESPUESTA FINAL:', [
                'respuesta' => $respuesta,
            ]);

            return response()->json(
                $respuesta
            );

        } catch (\Throwable $e) {

            Log::error('==============================================');
            Log::error('💥 ERROR EN API TICKETS');
            Log::error('==============================================');

            Log::error('Mensaje:', [
                'message' => $e->getMessage(),
            ]);

            Log::error('Archivo:', [
                'file' => $e->getFile(),
            ]);

            Log::error('Línea:', [
                'line' => $e->getLine(),
            ]);

            Log::error('Trace:', [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' =>
                    'Error interno al consultar los tickets',
                'debug' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], 500);
        }
    }

    public function show(
        Request $request,
        $id
    ) {
        Log::info('========================================');
        Log::info('🎫 TICKET API - SHOW');
        Log::info('========================================');

        try {
            $usuario = Auth::user();

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado',
                ], 401);
            }

            $ticket = TicketU::where(
                'id',
                $id
            )
                ->where(
                    'login',
                    $usuario->login
                )
                ->with([
                    'user.departamento.oficina',
                    'tomadoPor',
                    'solucion',
                    'solucion.solucionadoPor',
                ])
                ->first();

            if (!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'Ticket no encontrado.',
                ], 404);
            }

            $respuesta = [
                'success' => true,

                'ticket' =>
                    $this->formatearTicketCompleto(
                        $ticket,
                        $usuario
                    ),
            ];

            Log::info('📤 TICKET ENVIADO:', [
                'ticket' =>
                    $respuesta['ticket'],
            ]);

            return response()->json(
                $respuesta
            );

        } catch (\Throwable $e) {

            Log::error('========================================');
            Log::error('💥 ERROR EN TICKET API - SHOW');
            Log::error('========================================');

            Log::error('Mensaje:', [
                'message' => $e->getMessage(),
            ]);

            Log::error('Archivo:', [
                'file' => $e->getFile(),
            ]);

            Log::error('Línea:', [
                'line' => $e->getLine(),
            ]);

            Log::error('Trace:', [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' =>
                    'Error al obtener el ticket.',
                'error' =>
                    $e->getMessage(),
            ], 500);
        }
    }

    private function generarActividad($tickets)
    {
        $actividad = collect();

        foreach ($tickets as $ticket) {

            if ($ticket->created_at) {
                $actividad->push([
                    'fecha' =>
                        $ticket->created_at,

                    'texto' =>
                        'Tu ticket ' .
                        $ticket->folio .
                        ' se creó correctamente',

                    'tipo' => 'creado',

                    'color' => 'blue',

                    'ticket_id' =>
                        $ticket->id,

                    'folio' =>
                        $ticket->folio,
                ]);
            }

            if (
                $ticket->tomadoPor &&
                $ticket->fecha_tomado
            ) {
                $actividad->push([
                    'fecha' =>
                        $ticket->fecha_tomado,

                    'texto' =>
                        $ticket->tomadoPor->name .
                        ' tomó tu ticket ' .
                        $ticket->folio,

                    'tipo' => 'tomado',

                    'color' => 'green',

                    'ticket_id' =>
                        $ticket->id,

                    'folio' =>
                        $ticket->folio,

                    'usuario' => [
                        'login' =>
                            $ticket->tomadoPor->login,

                        'name' =>
                            $ticket->tomadoPor->name,

                        'email' =>
                            $ticket->tomadoPor->email,
                    ],
                ]);
            }

            if ($ticket->solucion) {

                $fechaSolucion =
                    $ticket->solucion->fecha_solucion
                    ??
                    $ticket->solucion->created_at;

                $nombreSolucionador =
                    $ticket
                        ->solucion
                        ->solucionadoPor
                        ?->name;

                if (!$nombreSolucionador) {
                    $nombreSolucionador =
                        $ticket
                            ->solucion
                            ->nombre_firmante;
                }

                $texto =
                    'Tu ticket ' .
                    $ticket->folio .
                    ' fue solucionado';

                if ($nombreSolucionador) {
                    $texto =
                        $nombreSolucionador .
                        ' solucionó tu ticket ' .
                        $ticket->folio;
                }

                $actividad->push([
                    'fecha' =>
                        $fechaSolucion,

                    'texto' =>
                        $texto,

                    'tipo' => 'solucionado',

                    'color' => 'green',

                    'ticket_id' =>
                        $ticket->id,

                    'folio' =>
                        $ticket->folio,

                    'usuario' =>
                        $ticket
                            ->solucion
                            ->solucionadoPor
                            ? [
                                'login' =>
                                    $ticket
                                        ->solucion
                                        ->solucionadoPor
                                        ->login,

                                'name' =>
                                    $ticket
                                        ->solucion
                                        ->solucionadoPor
                                        ->name,

                                'email' =>
                                    $ticket
                                        ->solucion
                                        ->solucionadoPor
                                        ->email,
                            ]
                            : null,
                ]);
            }
        }

        return $actividad
            ->sortByDesc('fecha')
            ->take(5)
            ->values();
    }

    private function obtenerTomadoPor($ticket)
    {
        if ($ticket->tomadoPor) {
            return [
                'login' =>
                    $ticket->tomadoPor->login,

                'name' =>
                    $ticket->tomadoPor->name,

                'email' =>
                    $ticket->tomadoPor->email,
            ];
        }

        if ($ticket->tomado_por) {
            return [
                'login' =>
                    $ticket->tomado_por,

                'name' =>
                    $ticket->tomado_por,
            ];
        }

        return null;
    }

    private function formatearTicket(
        $ticket,
        $usuario
    ) {
        $solucion = $ticket->solucion;

        $departamento =
            $ticket
                ->user
                ?->departamento
                ?->nombre
            ??
            $usuario
                ->departamento
            ??
            null;

        $oficina =
            $ticket
                ->user
                ?->departamento
                ?->oficina
                ?->nombre
            ??
            $usuario
                ->oficina
            ??
            null;

        $tomadoPor = $this->obtenerTomadoPor(
            $ticket
        );

        return [
            'id' =>
                $ticket->id,

            'folio' =>
                $ticket->folio,

            'titulo' =>
                $ticket->titulo,

            'tipo_falla' =>
                $ticket->tipo_falla,

            'equipo' =>
                $ticket->equipo,

            'prioridad' =>
                $ticket->prioridad,

            'descripcion' =>
                $ticket->descripcion,

            'estado' =>
                $ticket->estado,

            'departamento' =>
                $departamento,

            'oficina' =>
                $oficina,

            'asignado_a' =>
                $tomadoPor,

            'tomado_por' =>
                $tomadoPor,

            'fecha_asignacion' =>
                $ticket->fecha_tomado,

            'created_at' =>
                $ticket->created_at,

            'updated_at' =>
                $ticket->updated_at,

            'solucion' =>
                $solucion
                    ? $this->formatearSolucion(
                        $solucion
                    )
                    : null,
        ];
    }

    private function formatearTicketCompleto(
        $ticket,
        $usuario
    ) {
        $solucion = $ticket->solucion;

        $departamento =
            $ticket
                ->user
                ?->departamento
                ?->nombre
            ??
            $usuario
                ->departamento
            ??
            null;

        $oficina =
            $ticket
                ->user
                ?->departamento
                ?->oficina
                ?->nombre
            ??
            $usuario
                ->oficina
            ??
            null;

        $tomadoPor = $this->obtenerTomadoPor(
            $ticket
        );

        return [
            'id' =>
                $ticket->id,

            'folio' =>
                $ticket->folio,

            'titulo' =>
                $ticket->titulo,

            'tipo_falla' =>
                $ticket->tipo_falla,

            'equipo' =>
                $ticket->equipo,

            'prioridad' =>
                $ticket->prioridad,

            'descripcion' =>
                $ticket->descripcion,

            'afecta_otros' =>
                $ticket->afecta_otros,

            'es_recurrente' =>
                $ticket->es_recurrente,

            'comentarios' =>
                $ticket->comentarios,

            'evidencia' =>
                $ticket->evidencia,

            'estado' =>
                $ticket->estado,

            'departamento' =>
                $departamento,

            'oficina' =>
                $oficina,

            'asignado_a' =>
                $tomadoPor,

            'tomado_por' =>
                $tomadoPor,

            'fecha_asignacion' =>
                $ticket->fecha_tomado,

            'created_at' =>
                $ticket->created_at,

            'updated_at' =>
                $ticket->updated_at,

            'solucion' =>
                $solucion
                    ? $this->formatearSolucion(
                        $solucion
                    )
                    : null,
        ];
    }

    private function formatearSolucion(
        $solucion
    ) {
        return [
            'id' =>
                $solucion->id,

            'ticket_id' =>
                $solucion->ticket_id,

            'login' =>
                $solucion->login,

            'problema_solucionado' =>
                $solucion->problema_solucionado,

            'solucion' =>
                $solucion->solucion,

            'firma' =>
                $solucion->firma,

            'fecha_solucion' =>
                $solucion->fecha_solucion,

            'nombre_firmante' =>
                $solucion->nombre_firmante,

            'fecha_firma' =>
                $solucion->fecha_firma,

            'evidencia' =>
                $solucion->evidencia,

            'solucionado_por' =>
                $solucion->solucionado_por,

            'solucionado_por_usuario' =>
                $solucion->solucionadoPor
                    ? [
                        'login' =>
                            $solucion
                                ->solucionadoPor
                                ->login,

                        'name' =>
                            $solucion
                                ->solucionadoPor
                                ->name,

                        'email' =>
                            $solucion
                                ->solucionadoPor
                                ->email,
                    ]
                    : null,

            'created_at' =>
                $solucion->created_at,

            'updated_at' =>
                $solucion->updated_at,
        ];
    }

    public function marcarNotificacionLeida(Request $request, $id)
{
    try {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado',
            ], 401);
        }

        $notificacion = Notificacion::where('id', $id)
            ->where('login', $usuario->login)
            ->first();

        if (!$notificacion) {
            return response()->json([
                'success' => false,
                'message' => 'Notificación no encontrada',
            ], 404);
        }

        $notificacion->leida = true;
        $notificacion->save();

        return response()->json([
            'success' => true,
            'message' => 'Notificación marcada como leída',
            'notificacion' => [
                'id' => $notificacion->id,
                'leida' => $notificacion->leida,
            ],
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al marcar la notificación',
            'error' => $e->getMessage(),
        ], 500);
    }
}
}