<?php

namespace App\Http\Controllers;

use App\Models\TicketU;
use App\Models\User;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ticketController extends Controller
{
    public function create()
    {
        $usuario = Auth::user();

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

        return view(
            'user.ticket',
            compact(
                'notificaciones',
                'notificacionesNoLeidas'
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


        $empresaId = $usuario
            ->departamento
            ?->oficina
            ?->empresa_id;
            

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

        $ticket = TicketU::create([
            'folio' => $folio,
            'login' => $usuario->login,
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

        if ($empresaId) {
            $tecnicos = User::where(
                'role',
                'tecnologias'
            )
                ->where(
                    'login',
                    '!=',
                    $usuario->login
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
                Notificacion::create([
                    'login' => $tecnico->login,
                    'tipo' => 'ticket_nuevo',
                    'titulo' => 'Nuevo ticket recibido',
                    'mensaje' =>
                        "El usuario {$usuario->name} creó el ticket {$ticket->folio}: {$ticket->titulo}",
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
            }
        }

        return redirect()
            ->route('ticketusuario')
            ->with(
                'success',
                "Ticket {$folio} creado correctamente."
            );
    }
}
