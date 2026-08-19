<?php

namespace App\Http\Controllers;

use App\Models\TicketU;
use App\Models\Aviso;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD DEL USUARIO
    |--------------------------------------------------------------------------
    */

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


            // =====================================================
            // FECHA DE ASIGNACIÓN
            // =====================================================

            $fechaAsignacion = null;

            if ($ultimoTicketModel->fecha_tomado) {

                $fechaAsignacion = $ultimoTicketModel
                    ->fecha_tomado
                    ->timezone('America/Matamoros')
                    ->format('d M Y');
            }


            // =====================================================
            // FECHA DE REPORTE
            // =====================================================

            $fechaReporte = null;

            if ($ultimoTicketModel->created_at) {

                $fechaReporte = $ultimoTicketModel
                    ->created_at
                    ->timezone('America/Matamoros')
                    ->format('d M Y');
            }


            // =====================================================
            // ESTADO DE SOLUCIÓN
            // =====================================================

            $solucionado = (
                $ultimoTicketModel->estado === 'solucionado' ||
                $ultimoTicketModel->solucion !== null
            )
                ? 'Sí'
                : 'No';


            // =====================================================
            // INFORMACIÓN DEL ÚLTIMO TICKET
            // =====================================================

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

        $avisos = Aviso::orderByDesc('created_at')
            ->limit(3)
            ->get();


        // =========================================================
        // NOTIFICACIONES
        // =========================================================

        $notificaciones = Notificacion::where(
            'user_id',
            $usuario->id
        )
            ->latest('created_at')
            ->limit(10)
            ->get();


        // =========================================================
        // NOTIFICACIONES NO LEÍDAS
        // =========================================================

        $notificacionesNoLeidas = Notificacion::where(
            'user_id',
            $usuario->id
        )
            ->where('leida', false)
            ->count();


        // =========================================================
        // VISTA DASHBOARD
        // =========================================================

        return view(
            'user.index',
            compact(
                'resumen',
                'ultimoTicket',
                'ticketsRecientes',
                'avisos',
                'notificaciones',
                'notificacionesNoLeidas'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DETALLES DEL TICKET
    |--------------------------------------------------------------------------
    */

    public function verTicket(TicketU $ticket)
    {
        $usuario = Auth::user();


        // =========================================================
        // VERIFICAR QUE EL TICKET PERTENEZCA AL USUARIO
        // =========================================================

        if ($ticket->user_id !== $usuario->id) {
            abort(403);
        }


        // =========================================================
        // CARGAR RELACIONES DEL TICKET
        // =========================================================

        $ticket->load([
            'user.departamento.oficina',
            'tomadoPor',
            'solucion',
            'solucion.solucionadoPor',
        ]);


        // =========================================================
        // NOTIFICACIONES
        // =========================================================

        $notificaciones = Notificacion::where(
            'user_id',
            $usuario->id
        )
            ->latest('created_at')
            ->limit(10)
            ->get();


        // =========================================================
        // NOTIFICACIONES NO LEÍDAS
        // =========================================================

        $notificacionesNoLeidas = Notificacion::where(
            'user_id',
            $usuario->id
        )
            ->where('leida', false)
            ->count();


        // =========================================================
        // AVISOS
        // =========================================================

        $avisos = Aviso::orderByDesc('created_at')
            ->limit(3)
            ->get();


        // =========================================================
        // VISTA DETALLES
        // =========================================================

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