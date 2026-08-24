<?php

namespace App\Http\Controllers;

use App\Models\TicketU;
use App\Models\User;
use App\Models\Notificacion;
use App\Models\Solucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ticketController extends Controller
{
    public function create()
    {
        $usuario = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES
        |--------------------------------------------------------------------------
        */

        $notificaciones = Notificacion::where(
            'login',
            $usuario->login
        )
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $notificacionesNoLeidas = Notificacion::where(
            'login',
            $usuario->login
        )
            ->where(
                'leida',
                false
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | ÚLTIMO TICKET DEL USUARIO
        |--------------------------------------------------------------------------
        */

        $ultimoTicketModel = TicketU::where(
            'login',
            $usuario->login
        )
            ->orderByDesc('id')
            ->first();

        $ultimoTicket = null;

        if ($ultimoTicketModel) {

            /*
            |--------------------------------------------------------------------------
            | BUSCAR SOLUCIÓN DEL TICKET
            |--------------------------------------------------------------------------
            |
            | Si no existe registro en soluciones:
            | problema_solucionado = NULL
            |
            | Si existe:
            | 1 = Sí
            | 0 = No
            |
            */

            $solucion = Solucion::where(
                'ticket_id',
                $ultimoTicketModel->id
            )
                ->latest('id')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | ARMAR DATOS DEL ÚLTIMO TICKET
            |--------------------------------------------------------------------------
            */

            $ultimoTicket = [
                'id' =>
                    $ultimoTicketModel->id,

                'folio' =>
                    $ultimoTicketModel->folio,

                'titulo' =>
                    $ultimoTicketModel->titulo,

                'tipo_falla' =>
                    $ultimoTicketModel->tipo_falla,

                'fecha_reporte' =>
                    $ultimoTicketModel->created_at
                        ?->format('d/m/Y'),

                'departamento' =>
                    $ultimoTicketModel->departamento ?? null,

                'asignado_a' =>
                    $ultimoTicketModel->asignado_a ?? null,

                'oficina' =>
                    $ultimoTicketModel->oficina ?? null,

                'tomado_por' =>
                    $ultimoTicketModel->tomado_por ?? null,

                'estado' =>
                    $ultimoTicketModel->estado,

                'fecha_asignacion' =>
                    $ultimoTicketModel->fecha_asignacion ?? null,

                'prioridad' =>
                    $ultimoTicketModel->prioridad,

                /*
                |--------------------------------------------------------------------------
                | SOLUCIÓN
                |--------------------------------------------------------------------------
                */

                'solucion_id' =>
                    $ultimoTicketModel->solucion_id,

                'solucionado' =>
                    $solucion
                        ? $solucion->problema_solucionado
                        : null,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | VISTA
        |--------------------------------------------------------------------------
        */

        return view(
            'user.ticket',
            compact(
                'notificaciones',
                'notificacionesNoLeidas',
                'ultimoTicket'
            )
        );
    }

    public function store(Request $request)
    {
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
        ]);

        $usuario = Auth::user();

        $loginUsuario = trim(
            (string) $usuario->login
        );

        $empresaId = $usuario
            ->departamento
            ?->oficina
            ?->empresa_id;

        Log::info(
            '========== CREANDO NUEVO TICKET =========='
        );

        Log::info(
            'DATOS DEL USUARIO',
            [
                'id' =>
                    $usuario->id,

                'login' =>
                    $loginUsuario,

                'name' =>
                    $usuario->name,

                'role' =>
                    $usuario->role,

                'priv_admin' =>
                    $usuario->priv_admin,

                'empresa_id' =>
                    $empresaId,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | EVIDENCIAS DEL TICKET
        |--------------------------------------------------------------------------
        */

        $filePaths = [];

        if ($request->hasFile('evidencia')) {

            foreach (
                $request->file('evidencia')
                as $file
            ) {

                if (!$file->isValid()) {
                    continue;
                }

                $filePaths[] = $file->store(
                    'evidencia_tickets',
                    'public'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | GENERAR FOLIO
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | CREAR TICKET
        |--------------------------------------------------------------------------
        */

        $ticket = TicketU::create([
            'folio' =>
                $folio,

            'login' =>
                $loginUsuario,

            'titulo' =>
                $validated['titulo'],

            'tipo_falla' =>
                $validated['tipo_falla'],

            'equipo' =>
                $validated['equipo'] ?? null,

            'prioridad' =>
                $validated['prioridad'],

            'descripcion' =>
                $validated['descripcion'],

            'afecta_otros' =>
                $validated['afecta_otros'],

            'es_recurrente' =>
                $validated['es_recurrente'],

            'comentarios' =>
                $validated['comentarios'] ?? null,

            'evidencia' =>
                $filePaths,
        ]);

        Log::info(
            'TICKET CREADO',
            [
                'ticket_id' =>
                    $ticket->id,

                'folio' =>
                    $ticket->folio,

                'login' =>
                    $loginUsuario,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | DESTINATARIOS
        |--------------------------------------------------------------------------
        */

        $destinatarios = collect();

        /*
        |--------------------------------------------------------------------------
        | TECNOLOGÍAS DE LA MISMA EMPRESA
        |--------------------------------------------------------------------------
        */

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

                $destinatarios->push(
                    $tecnico
                );
            }

            Log::info(
                'TECNICOS DE LA MISMA EMPRESA',
                [
                    'empresa_id' =>
                        $empresaId,

                    'cantidad' =>
                        $tecnicos->count(),

                    'usuarios' =>
                        $tecnicos
                            ->map(function ($tecnico) {

                                return [
                                    'id' =>
                                        $tecnico->id,

                                    'login' =>
                                        $tecnico->login,

                                    'name' =>
                                        $tecnico->name,

                                    'role' =>
                                        $tecnico->role,

                                    'priv_admin' =>
                                        $tecnico->priv_admin,
                                ];
                            })
                            ->values()
                            ->toArray(),
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | GERENTE TI
        |--------------------------------------------------------------------------
        */

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

            $destinatarios->push(
                $gerente
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SOPORTE TÉCNICO
        |--------------------------------------------------------------------------
        */

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

            $destinatarios->push(
                $usuarioSoporte
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ELIMINAR DUPLICADOS
        |--------------------------------------------------------------------------
        */

        $destinatarios = $destinatarios
            ->unique('login')
            ->values();

        Log::info(
            'DESTINATARIOS FINALES DE LA NOTIFICACIÓN',
            [
                'cantidad' =>
                    $destinatarios->count(),

                'destinatarios' =>
                    $destinatarios
                        ->map(function ($destinatario) {

                            return [
                                'id' =>
                                    $destinatario->id,

                                'login' =>
                                    $destinatario->login,

                                'name' =>
                                    $destinatario->name,

                                'role' =>
                                    $destinatario->role,

                                'priv_admin' =>
                                    $destinatario->priv_admin,
                            ];
                        })
                        ->toArray(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | CREAR NOTIFICACIONES
        |--------------------------------------------------------------------------
        */

        foreach (
            $destinatarios
            as $destinatario
        ) {

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

                    'leida' =>
                        false,

                    'icono' =>
                        'ticket',

                    'color' =>
                        'blue',
                ]);

                Log::info(
                    'NOTIFICACIÓN CREADA',
                    [
                        'notificacion_id' =>
                            $notificacion->id,

                        'destinatario_id' =>
                            $destinatario->id,

                        'destinatario_login' =>
                            $destinatario->login,

                        'destinatario_nombre' =>
                            $destinatario->name,

                        'destinatario_role' =>
                            $destinatario->role,

                        'destinatario_priv_admin' =>
                            $destinatario->priv_admin,

                        'ticket_id' =>
                            $ticket->id,

                        'ticket_folio' =>
                            $ticket->folio,
                    ]
                );

            } catch (\Throwable $e) {

                Log::error(
                    'ERROR CREANDO NOTIFICACIÓN',
                    [
                        'destinatario' =>
                            $destinatario->login,

                        'ticket_id' =>
                            $ticket->id,

                        'error' =>
                            $e->getMessage(),

                        'archivo' =>
                            $e->getFile(),

                        'linea' =>
                            $e->getLine(),
                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FINALIZAR
        |--------------------------------------------------------------------------
        */

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