<?php

namespace App\Http\Controllers;

use App\Models\TicketU;
use App\Models\Aviso;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        $resumen = [
            'total' => TicketU::where(
                'user_id',
                $usuario->id
            )->count(),

            'abiertos' => TicketU::where(
                'user_id',
                $usuario->id
            )
                ->where('estado', 'pendiente')
                ->count(),

            'en_proceso' => TicketU::where(
                'user_id',
                $usuario->id
            )
                ->where('estado', 'en proceso')
                ->count(),

            'solucionados' => TicketU::where(
                'user_id',
                $usuario->id
            )
                ->where('estado', 'solucionado')
                ->count(),

            'cancelados' => TicketU::where(
                'user_id',
                $usuario->id
            )
                ->where('estado', 'cancelado')
                ->count(),
        ];

        $ultimoTicketModel = TicketU::with([
            'user.departamento.oficina',
            'solucion',
            'tomadoPor',
        ])
            ->where('user_id', $usuario->id)
            ->latest('created_at')
            ->first();

        $ultimoTicket = null;

        if ($ultimoTicketModel) {

            $departamento = $ultimoTicketModel
                ->user
                ?->departamento
                ?->nombre;

            $oficina = $ultimoTicketModel
                ->user
                ?->departamento
                ?->oficina
                ?->nombre;

            $tomadoPor = $ultimoTicketModel
                ->tomadoPor
                ?->name;

            $fechaAsignacion = null;

            if ($ultimoTicketModel->fecha_tomado) {

                $fechaAsignacion = $ultimoTicketModel
                    ->fecha_tomado
                    ->timezone('America/Matamoros')
                    ->format('d M Y');
            }

            $fechaReporte = null;

            if ($ultimoTicketModel->created_at) {

                $fechaReporte = $ultimoTicketModel
                    ->created_at
                    ->timezone('America/Matamoros')
                    ->format('d M Y');
            }

            $solucionado = (
                $ultimoTicketModel->estado === 'solucionado' ||
                $ultimoTicketModel->solucion !== null
            )
                ? 'Sí'
                : 'No';

            $ultimoTicket = [

                'id' =>
                    $ultimoTicketModel->id,

                'folio' =>
                    $ultimoTicketModel->folio,

                'tipo_falla' =>
                    $ultimoTicketModel->tipo_falla,

                'fecha_reporte' =>
                    $fechaReporte,

                'departamento' =>
                    $departamento,

                'oficina' =>
                    $oficina,

                'tomado_por' =>
                    $tomadoPor,

                'estado' =>
                    $ultimoTicketModel->estado,

                'fecha_asignacion' =>
                    $fechaAsignacion,

                'prioridad' =>
                    $ultimoTicketModel->prioridad,

                'solucionado' =>
                    $solucionado,
            ];
        }

        $ticketsRecientes = TicketU::with([
            'user.departamento.oficina',
            'tomadoPor',
            'solucion',
        ])
            ->where('user_id', $usuario->id)
            ->latest('created_at')
            ->limit(5)
            ->get();

        $actividad = collect();

        foreach ($ticketsRecientes as $ticket) {

            $actividad->push([
                'fecha' => $ticket->created_at,
                'texto' => 'Tu ticket ' . $ticket->folio . ' se creó correctamente',
                'color' => 'bg-blue-600',
            ]);

            if ($ticket->tomadoPor && $ticket->fecha_tomado) {

                $actividad->push([
                    'fecha' => $ticket->fecha_tomado,
                    'texto' => $ticket->tomadoPor->name . ' tomó tu ticket ' . $ticket->folio,
                    'color' => 'bg-green-600',
                ]);
            }

            if ($ticket->solucion) {

                $actividad->push([
                    'fecha' => $ticket->solucion->created_at,
                    'texto' => 'Tu ticket ' . $ticket->folio . ' fue solucionado',
                    'color' => 'bg-green-600',
                ]);
            }
        }

        $actividad = $actividad
            ->sortByDesc('fecha')
            ->take(5)
            ->values();

        $avisos = Aviso::orderByDesc('created_at')
            ->limit(3)
            ->get();

        $notificaciones = Notificacion::where(
            'user_id',
            $usuario->id
        )
            ->latest('created_at')
            ->limit(10)
            ->get();

        $notificacionesNoLeidas = Notificacion::where(
            'user_id',
            $usuario->id
        )
            ->where('leida', false)
            ->count();

        return view(
            'user.index',
            compact(
                'resumen',
                'ultimoTicket',
                'ticketsRecientes',
                'actividad',
                'avisos',
                'notificaciones',
                'notificacionesNoLeidas'
            )
        );
    }

    public function verTicket(TicketU $ticket)
    {
        $usuario = Auth::user();

        if ($ticket->user_id !== $usuario->id) {
            abort(403);
        }

        $ticket->load([
            'user.departamento.oficina',
            'tomadoPor',
            'solucion',
            'solucion.solucionadoPor',
        ]);

        $notificaciones = Notificacion::where(
            'user_id',
            $usuario->id
        )
            ->latest('created_at')
            ->limit(10)
            ->get();

        $notificacionesNoLeidas = Notificacion::where(
            'user_id',
            $usuario->id
        )
            ->where('leida', false)
            ->count();

        $avisos = Aviso::orderByDesc('created_at')
            ->limit(3)
            ->get();

        return view(
            'user.detalles',
            compact(
                'ticket',
                'notificaciones',
                'notificacionesNoLeidas',
                'avisos'
            )
        );
    }
}