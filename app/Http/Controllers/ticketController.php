<?php

namespace App\Http\Controllers;

use App\Models\Dispositivos;
use App\Models\Notificacion;
use App\Models\Solucion;
use App\Models\TicketU;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ticketController extends Controller
{
    public function create()
    {
        $usuario = Auth::user();

        $notificaciones = Notificacion::where('login', $usuario->login)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $notificacionesNoLeidas = Notificacion::where('login', $usuario->login)
            ->where('leida', false)
            ->count();

        $ultimoTicketModel = TicketU::where('login', $usuario->login)
            ->orderByDesc('id')
            ->first();

        $ultimoTicket = null;

        if ($ultimoTicketModel) {
            $solucion = Solucion::where('ticket_id', $ultimoTicketModel->id)
                ->latest('id')
                ->first();

            $ultimoTicket = [
                'id' => $ultimoTicketModel->id,
                'folio' => $ultimoTicketModel->folio,
                'titulo' => $ultimoTicketModel->titulo,
                'tipo_falla' => $ultimoTicketModel->tipo_falla,
                'fecha_reporte' => $ultimoTicketModel->created_at?->format('d/m/Y'),
                'departamento' => $ultimoTicketModel->departamento ?? null,
                'asignado_a' => $ultimoTicketModel->asignado_a ?? null,
                'oficina' => $ultimoTicketModel->oficina ?? null,
                'tomado_por' => $ultimoTicketModel->tomado_por ?? null,
                'estado' => $ultimoTicketModel->estado,
                'fecha_asignacion' => $ultimoTicketModel->fecha_asignacion ?? null,
                'prioridad' => $ultimoTicketModel->prioridad,
                'solucion_id' => $ultimoTicketModel->solucion_id,
                'solucionado' => $solucion?->problema_solucionado,
            ];
        }

        $dispositivos = Dispositivos::where('login', $usuario->login)
            ->where('estado', 'vinculado')
            ->orderBy('nombre_equipo')
            ->get();

        return view('user.ticket', compact(
            'notificaciones',
            'notificacionesNoLeidas',
            'ultimoTicket',
            'dispositivos'
        ));
    }

    public function store(Request $request)
    {
        $usuario = Auth::user();

        $loginUsuario = trim((string) $usuario->login);

        $validated = $request->validate([
            'titulo' => [
                'required',
                'string',
                'max:255',
            ],

            'tipo_falla' => [
                'required',
                'string',
            ],

            'equipo' => [
                'required_if:tipo_falla,Equipo',
                'nullable',
                'string',
                'max:255',
                Rule::exists('dispositivos', 'nombre_equipo')
                    ->where(function ($query) use ($loginUsuario) {
                        $query->where('login', $loginUsuario)
                            ->where('estado', 'vinculado');
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
            'titulo.required' => 'Debes ingresar un título.',
            'tipo_falla.required' => 'Debes seleccionar el tipo de falla.',
            'equipo.required_if' => 'Debes seleccionar el equipo que presenta la falla.',
            'equipo.exists' => 'El equipo seleccionado no es válido o ya no se encuentra vinculado a tu usuario.',
            'prioridad.required' => 'Debes seleccionar una prioridad.',
            'prioridad.in' => 'La prioridad seleccionada no es válida.',
            'descripcion.required' => 'Debes ingresar una descripción.',
            'afecta_otros.required' => 'Debes indicar si la falla afecta a otros usuarios.',
            'es_recurrente.required' => 'Debes indicar si la falla es recurrente.',
        ]);

        $empresaId = $usuario->departamento?->oficina?->empresa_id;

        Log::info('========== CREANDO NUEVO TICKET ==========');

        Log::info('DATOS DEL USUARIO', [
            'id' => $usuario->id,
            'login' => $loginUsuario,
            'name' => $usuario->name,
            'role' => $usuario->role,
            'priv_admin' => $usuario->priv_admin,
            'empresa_id' => $empresaId,
        ]);

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

            $folio = 'TKT-' .
                $año .
                '-' .
                str_pad(
                    $numero,
                    5,
                    '0',
                    STR_PAD_LEFT
                );
        } while (
            TicketU::where('folio', $folio)->exists()
        );

        $ticket = TicketU::create([
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

        Log::info('TICKET CREADO', [
            'ticket_id' => $ticket->id,
            'folio' => $ticket->folio,
            'login' => $loginUsuario,
            'equipo' => $ticket->equipo,
        ]);

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

            Log::info('TECNICOS DE LA MISMA EMPRESA', [
                'empresa_id' => $empresaId,
                'cantidad' => $tecnicos->count(),
                'usuarios' => $tecnicos->map(function ($tecnico) {
                    return [
                        'id' => $tecnico->id,
                        'login' => $tecnico->login,
                        'name' => $tecnico->name,
                        'role' => $tecnico->role,
                        'priv_admin' => $tecnico->priv_admin,
                    ];
                })->values()->toArray(),
            ]);
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

        Log::info(
            'DESTINATARIOS FINALES DE LA NOTIFICACIÓN',
            [
                'cantidad' => $destinatarios->count(),
                'destinatarios' => $destinatarios
                    ->map(function ($destinatario) {
                        return [
                            'id' => $destinatario->id,
                            'login' => $destinatario->login,
                            'name' => $destinatario->name,
                            'role' => $destinatario->role,
                            'priv_admin' => $destinatario->priv_admin,
                        ];
                    })
                    ->toArray(),
            ]
        );

        foreach ($destinatarios as $destinatario) {
            try {
                $notificacion = Notificacion::create([
                    'login' => $destinatario->login,
                    'tipo' => 'ticket_nuevo',
                    'titulo' => 'Nuevo ticket recibido',
                    'mensaje' => "El usuario {$usuario->name} creó el ticket {$ticket->folio}: {$ticket->titulo}",
                    'url' => route(
                        'tickettecnologias',
                        [
                            'ticket' => $ticket->id,
                        ]
                    ),
                    'leida' => false,
                    'icono' => 'ticket',
                    'color' => 'blue',
                ]);

                Log::info('NOTIFICACIÓN CREADA', [
                    'notificacion_id' => $notificacion->id,
                    'destinatario_id' => $destinatario->id,
                    'destinatario_login' => $destinatario->login,
                    'destinatario_nombre' => $destinatario->name,
                    'destinatario_role' => $destinatario->role,
                    'destinatario_priv_admin' => $destinatario->priv_admin,
                    'ticket_id' => $ticket->id,
                    'ticket_folio' => $ticket->folio,
                ]);
            } catch (\Throwable $e) {
                Log::error('ERROR CREANDO NOTIFICACIÓN', [
                    'destinatario' => $destinatario->login,
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage(),
                    'archivo' => $e->getFile(),
                    'linea' => $e->getLine(),
                ]);
            }
        }

        Log::info(
            '========== FINALIZÓ CREACIÓN DEL TICKET =========='
        );

        return redirect()
            ->route('ticketusuario')
            ->with(
                'success',
                "Ticket {$folio} creado correctamente."
            );
    }
}
