<?php

namespace App\Http\Controllers;

use App\Models\TicketU;
use App\Models\Aviso;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        // =========================================================
        // RESUMEN DE TICKETS
        // =========================================================

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


        // =========================================================
        // ÚLTIMO TICKET
        // =========================================================

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

            // Fecha de asignación

            $fechaAsignacion = null;

            if ($ultimoTicketModel->fecha_tomado) {

                $fechaAsignacion = $ultimoTicketModel
                    ->fecha_tomado
                    ->timezone('America/Matamoros')
                    ->format('d M Y');
            }


            // Fecha de reporte

            $fechaReporte = null;

            if ($ultimoTicketModel->created_at) {

                $fechaReporte = $ultimoTicketModel
                    ->created_at
                    ->timezone('America/Matamoros')
                    ->format('d M Y');
            }


            // Estado de solución

            $solucionado = (
                $ultimoTicketModel->estado === 'solucionado' ||
                $ultimoTicketModel->solucion !== null
            )
                ? 'Sí'
                : 'No';


            $ultimoTicket = [

                'id' => $ultimoTicketModel->id,

                'folio' => $ultimoTicketModel->folio,

                'tipo_falla' => $ultimoTicketModel->tipo_falla,

                'fecha_reporte' => $fechaReporte,

                'departamento' => $departamento,

                'oficina' => $oficina,

                'tomado_por' => $tomadoPor,

                'estado' => $ultimoTicketModel->estado,

                'fecha_asignacion' => $fechaAsignacion,

                'prioridad' => $ultimoTicketModel->prioridad,

                'solucionado' => $solucionado,
            ];
        }


        // =========================================================
        // TICKETS RECIENTES
        // =========================================================

        $ticketsRecientes = TicketU::with([
            'user.departamento.oficina',
            'tomadoPor',
            'solucion',
        ])
            ->where('user_id', $usuario->id)
            ->latest('created_at')
            ->limit(5)
            ->get();


        // =========================================================
        // AVISOS
        // =========================================================
        //
        // Se muestran únicamente los 3 avisos más recientes.
        // Se ordenan por created_at porque fecha_inicio representa
        // cuándo comenzará el aviso, no cuándo fue publicado.
        //
        // Campos disponibles:
        // titulo
        // descripcion
        // importancia
        // tipo
        // fecha_inicio
        //
        // =========================================================

        $avisos = Aviso::orderByDesc('created_at')
            ->limit(3)
            ->get();


        // =========================================================
        // VISTA
        // =========================================================

        return view(
            'user.index',
            compact(
                'resumen',
                'ultimoTicket',
                'ticketsRecientes',
                'avisos'
            )
        );
    }


    // =============================================================
    // DETALLES DEL TICKET
    // =============================================================

    public function verTicket(TicketU $ticket)
    {
        // Verificar que el ticket pertenezca al usuario autenticado

        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }


        // Cargar relaciones necesarias

        $ticket->load([
            'user.departamento.oficina',
            'tomadoPor',
            'solucion',
        ]);


        return view(
            'user.detalles',
            compact('ticket')
        );
    }
}
