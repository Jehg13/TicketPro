<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dispositivos;
use App\Models\Notificacion;
use App\Models\Solucion;
use App\Models\TicketU;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TicketApiController extends Controller
{
    public function equipos()
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        $loginUsuario = trim((string) $usuario->login);

        $equipos = Dispositivos::query()
            ->where('login', $loginUsuario)
            ->where('estado', 'Vinculado')
            ->orderBy('nombre_equipo')
            ->get([
                'id',
                'nombre_equipo',
                'id_equipo',
            ]);

        return response()->json([
            'success' => true,
            'equipos' => $equipos,
        ]);
    }

    public function store(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        $loginUsuario = trim((string) $usuario->login);

        try {
            $validated = $request->validate([
                'titulo' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'tipo_falla' => [
                    'required',
                    'string',
                    'in:hardware,redes,software',
                ],

                'equipo' => [
                    'required_if:tipo_falla,hardware',
                    'nullable',
                    'string',
                    'max:255',
                    Rule::exists('dispositivos', 'nombre_equipo')
                        ->where(function ($query) use ($loginUsuario) {
                            $query
                                ->where('login', $loginUsuario)
                                ->where('estado', 'Vinculado');
                        }),
                ],

                'prioridad' => [
                    'required',
                    'in:Critica,Alta,Media,Normal',
                ],

                'descripcion' => [
                    'required',
                    'string',
                ],

                'afecta_otros' => [
                    'required',
                    'boolean',
                ],

                'es_recurrente' => [
                    'required',
                    'boolean',
                ],

                'comentarios' => [
                    'nullable',
                    'string',
                ],

                'evidencia.*' => [
                    'nullable',
                    'file',
                    'mimes:jpg,jpeg,png,pdf,mp4',
                    'max:10240',
                ],
            ], [
                'titulo.required' =>
                    'Debes ingresar un título.',

                'tipo_falla.required' =>
                    'Debes seleccionar el tipo de falla.',

                'tipo_falla.in' =>
                    'El tipo de falla seleccionado no es válido.',

                'equipo.required_if' =>
                    'Debes seleccionar el equipo que presenta la falla.',

                'equipo.exists' =>
                    'El equipo seleccionado no es válido o ya no se encuentra vinculado a tu usuario.',

                'prioridad.required' =>
                    'Debes seleccionar una prioridad.',

                'prioridad.in' =>
                    'La prioridad seleccionada no es válida.',

                'descripcion.required' =>
                    'Debes ingresar una descripción.',

                'afecta_otros.required' =>
                    'Debes indicar si la falla afecta a otros usuarios.',

                'es_recurrente.required' =>
                    'Debes indicar si la falla es recurrente.',
            ]);

            $empresaId = $usuario
                ->departamento
                ?->oficina
                ?->empresa_id;

            Log::info(
                '========== CREANDO TICKET DESDE API =========='
            );

            Log::info(
                'DATOS DEL USUARIO API',
                [
                    'id' => $usuario->id,
                    'login' => $loginUsuario,
                    'name' => $usuario->name,
                    'role' => $usuario->role,
                    'priv_admin' => $usuario->priv_admin,
                    'empresa_id' => $empresaId,
                ]
            );

            $filePaths = [];

            if ($request->hasFile('evidencia')) {
                foreach ($request->file('evidencia') as $file) {
                    if (!$file->isValid()) {
                        continue;
                    }

                    $filePaths[] = $file->store(
                        'evidencia_tickets',
                        'public'
                    );
                }
            }

            $año = date('Y');

            do {
                $ultimoTicket = TicketU::where(
                    'folio',
                    'like',
                    "TKT-{$año}-%"
                )
                    ->orderByDesc('id')
                    ->first();

                if ($ultimoTicket) {
                    $ultimoNumero = (int) substr(
                        $ultimoTicket->folio,
                        -5
                    );

                    $numero = $ultimoNumero + 1;
                } else {
                    $numero = 1;
                }

                $folio =
                    'TKT-' .
                    $año .
                    '-' .
                    str_pad(
                        $numero,
                        5,
                        '0',
                        STR_PAD_LEFT
                    );
            } while (
                TicketU::where(
                    'folio',
                    $folio
                )->exists()
            );

            $ticket = DB::transaction(
                function () use (
                    $validated,
                    $loginUsuario,
                    $filePaths,
                    $folio
                ) {
                    return TicketU::create([
                        'folio' => $folio,
                        'login' => $loginUsuario,
                        'titulo' => $validated['titulo'],
                        'tipo_falla' => $validated['tipo_falla'],
                        'equipo' => $validated['equipo'] ?? null,
                        'prioridad' => $validated['prioridad'],
                        'descripcion' => $validated['descripcion'],
                        'afecta_otros' => $validated['afecta_otros'],
                        'es_recurrente' => $validated['es_recurrente'],
                        'comentarios' => $validated['comentarios'] ?? null,
                        'evidencia' => $filePaths,
                    ]);
                }
            );

            Log::info(
                'TICKET API CREADO',
                [
                    'ticket_id' => $ticket->id,
                    'folio' => $ticket->folio,
                    'login' => $loginUsuario,
                    'equipo' => $ticket->equipo,
                ]
            );

            $destinatarios = collect();

            if ($empresaId) {
                $tecnicos = User::query()
                    ->whereRaw(
                        'LOWER(TRIM(role)) = ?',
                        ['tecnologias']
                    )
                    ->whereRaw(
                        'TRIM(login) != ?',
                        [$loginUsuario]
                    )
                    ->whereHas(
                        'departamento.oficina',
                        function ($query) use ($empresaId) {
                            $query->where(
                                'empresa_id',
                                $empresaId
                            );
                        }
                    )
                    ->get();

                foreach ($tecnicos as $tecnico) {
                    $destinatarios->push($tecnico);
                }
            }

            $gerentes = User::query()
                ->whereRaw(
                    'LOWER(TRIM(role)) = ?',
                    ['gerente ti']
                )
                ->whereRaw(
                    'UPPER(TRIM(priv_admin)) = ?',
                    ['Y']
                )
                ->whereRaw(
                    'TRIM(login) != ?',
                    [$loginUsuario]
                )
                ->get();

            foreach ($gerentes as $gerente) {
                $destinatarios->push($gerente);
            }

            $soporte = User::query()
                ->where(function ($query) {
                    $query
                        ->whereRaw(
                            'LOWER(TRIM(role)) = ?',
                            ['soporte tecnico']
                        )
                        ->orWhereRaw(
                            'LOWER(TRIM(role)) = ?',
                            ['soporte técnico']
                        );
                })
                ->whereRaw(
                    'UPPER(TRIM(priv_admin)) = ?',
                    ['Y']
                )
                ->whereRaw(
                    'TRIM(login) != ?',
                    [$loginUsuario]
                )
                ->get();

            foreach ($soporte as $usuarioSoporte) {
                $destinatarios->push($usuarioSoporte);
            }

            $destinatarios = $destinatarios
                ->unique('login')
                ->values();

            foreach ($destinatarios as $destinatario) {
                try {
                    $notificacion = Notificacion::create([
                        'login' =>
                            $destinatario->login,

                        'tipo' =>
                            'ticket_nuevo',

                        'titulo' =>
                            'Nuevo ticket recibido',

                        'mensaje' =>
                            "El usuario {$usuario->name} creó el ticket {$ticket->folio}: {$ticket->titulo}",

                        'url' =>
                            route(
                                'tickettecnologias',
                                [
                                    'ticket' =>
                                        $ticket->id,
                                ]
                            ),

                        'leida' => false,

                        'icono' => 'ticket',

                        'color' => 'blue',
                    ]);

                    Log::info(
                        'NOTIFICACIÓN API CREADA',
                        [
                            'notificacion_id' =>
                                $notificacion->id,

                            'destinatario_login' =>
                                $destinatario->login,

                            'ticket_id' =>
                                $ticket->id,

                            'ticket_folio' =>
                                $ticket->folio,
                        ]
                    );
                } catch (\Throwable $e) {
                    Log::error(
                        'ERROR CREANDO NOTIFICACIÓN API',
                        [
                            'destinatario' =>
                                $destinatario->login,

                            'ticket_id' =>
                                $ticket->id,

                            'error' =>
                                $e->getMessage(),
                        ]
                    );
                }
            }

            $solucion = Solucion::where(
                'ticket_id',
                $ticket->id
            )
                ->latest('id')
                ->first();

            return response()->json([
                'success' => true,

                'message' =>
                    "Ticket {$ticket->folio} creado correctamente.",

                'ticket' => [
                    'id' => $ticket->id,
                    'folio' => $ticket->folio,
                    'login' => $ticket->login,
                    'titulo' => $ticket->titulo,
                    'tipo_falla' => $ticket->tipo_falla,
                    'equipo' => $ticket->equipo,
                    'prioridad' => $ticket->prioridad,
                    'descripcion' => $ticket->descripcion,
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
                        $ticket->departamento,
                    'oficina' =>
                        $ticket->oficina,
                    'asignado_a' =>
                        $ticket->asignado_a,
                    'tomado_por' =>
                        $ticket->tomado_por,
                    'fecha_asignacion' =>
                        $ticket->fecha_asignacion,
                    'solucion_id' =>
                        $ticket->solucion_id,
                    'solucionado' =>
                        $solucion?->problema_solucionado,
                    'fecha_reporte' =>
                        $ticket->created_at?->format(
                            'd/m/Y H:i:s'
                        ),
                    'created_at' =>
                        $ticket->created_at,
                ],

                'notificaciones_enviadas' =>
                    $destinatarios->count(),
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Los datos enviados no son válidos.',
                'errors' =>
                    $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error(
                'ERROR CREANDO TICKET DESDE API',
                [
                    'login' => $loginUsuario,
                    'error' => $e->getMessage(),
                    'archivo' => $e->getFile(),
                    'linea' => $e->getLine(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    'No se pudo crear el ticket.',
                'error' =>
                    config('app.debug')
                        ? $e->getMessage()
                        : null,
            ], 500);
        }
    }
    
}