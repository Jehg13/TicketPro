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

        /*
        |--------------------------------------------------------------------------
        | RESUMEN DE TICKETS
        |--------------------------------------------------------------------------
        |
        | Ahora TicketU se relaciona con el usuario mediante:
        |
        | ticket_u_s.login = users.login
        |
        */

        $resumen = [

            'total' => TicketU::where(
                'login',
                $usuario->login
            )->count(),

            'abiertos' => TicketU::where(
                'login',
                $usuario->login
            )
                ->where(
                    'estado',
                    'pendiente'
                )
                ->count(),

            'en_proceso' => TicketU::where(
                'login',
                $usuario->login
            )
                ->where(
                    'estado',
                    'en proceso'
                )
                ->count(),

            'solucionados' => TicketU::where(
                'login',
                $usuario->login
            )
                ->where(
                    'estado',
                    'solucionado'
                )
                ->count(),

            'cancelados' => TicketU::where(
                'login',
                $usuario->login
            )
                ->where(
                    'estado',
                    'cancelado'
                )
                ->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | ÚLTIMO TICKET
        |--------------------------------------------------------------------------
        */

        $ultimoTicketModel = TicketU::with([

            'user.departamento.oficina',

            'solucion',

            'tomadoPor',

        ])
            ->where(
                'login',
                $usuario->login
            )
            ->latest('created_at')
            ->first();


        $ultimoTicket = null;


        if ($ultimoTicketModel) {

            /*
            |--------------------------------------------------------------------------
            | DEPARTAMENTO
            |--------------------------------------------------------------------------
            */

            $departamento =
                $ultimoTicketModel
                    ->user
                    ?->departamento
                    ?->nombre;


            /*
            |--------------------------------------------------------------------------
            | OFICINA
            |--------------------------------------------------------------------------
            */

            $oficina =
                $ultimoTicketModel
                    ->user
                    ?->departamento
                    ?->oficina
                    ?->nombre;


            /*
            |--------------------------------------------------------------------------
            | QUIÉN TOMÓ EL TICKET
            |--------------------------------------------------------------------------
            */

            $tomadoPor =
                $ultimoTicketModel
                    ->tomadoPor
                    ?->name;


            /*
            |--------------------------------------------------------------------------
            | FECHA DE ASIGNACIÓN
            |--------------------------------------------------------------------------
            */

            $fechaAsignacion = null;


            if ($ultimoTicketModel->fecha_tomado) {

                $fechaAsignacion =
                    $ultimoTicketModel
                        ->fecha_tomado
                        ->timezone('America/Matamoros')
                        ->format('d M Y');
            }


            /*
            |--------------------------------------------------------------------------
            | FECHA DE REPORTE
            |--------------------------------------------------------------------------
            */

            $fechaReporte = null;


            if ($ultimoTicketModel->created_at) {

                $fechaReporte =
                    $ultimoTicketModel
                        ->created_at
                        ->timezone('America/Matamoros')
                        ->format('d M Y');
            }


            /*
            |--------------------------------------------------------------------------
            | ESTADO DE SOLUCIÓN
            |--------------------------------------------------------------------------
            */

            $solucionado = (

                $ultimoTicketModel->estado ===
                'solucionado'

                ||

                $ultimoTicketModel->solucion !== null

            )
                ? 'Sí'
                : 'No';


            /*
            |--------------------------------------------------------------------------
            | INFORMACIÓN DEL ÚLTIMO TICKET
            |--------------------------------------------------------------------------
            */

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


        /*
        |--------------------------------------------------------------------------
        | TICKETS RECIENTES
        |--------------------------------------------------------------------------
        */

        $ticketsRecientes = TicketU::with([

            'user.departamento.oficina',

            'tomadoPor',

            'solucion',

        ])
            ->where(
                'login',
                $usuario->login
            )
            ->latest('created_at')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ACTIVIDAD
        |--------------------------------------------------------------------------
        */

        $actividad = collect();


        foreach (
            $ticketsRecientes
            as $ticket
        ) {

            /*
            |--------------------------------------------------------------------------
            | TICKET CREADO
            |--------------------------------------------------------------------------
            */

            $actividad->push([

                'fecha' =>
                    $ticket->created_at,

                'texto' =>
                    'Tu ticket ' .
                    $ticket->folio .
                    ' se creó correctamente',

                'color' =>
                    'bg-blue-600',
            ]);


            /*
            |--------------------------------------------------------------------------
            | TICKET TOMADO
            |--------------------------------------------------------------------------
            */

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

                    'color' =>
                        'bg-green-600',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | TICKET SOLUCIONADO
            |--------------------------------------------------------------------------
            */

            if ($ticket->solucion) {

                $actividad->push([

                    'fecha' =>
                        $ticket
                            ->solucion
                            ->created_at,

                    'texto' =>
                        'Tu ticket ' .
                        $ticket->folio .
                        ' fue solucionado',

                    'color' =>
                        'bg-green-600',
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | ORDENAR ACTIVIDAD
        |--------------------------------------------------------------------------
        */

        $actividad =
            $actividad
                ->sortByDesc('fecha')
                ->take(5)
                ->values();


        /*
        |--------------------------------------------------------------------------
        | AVISOS
        |--------------------------------------------------------------------------
        */

        $avisos =
            Aviso::orderByDesc(
                'created_at'
            )
                ->limit(3)
                ->get();


        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES
        |--------------------------------------------------------------------------
        |
        | notificaciones.login = users.login
        |
        */

        $notificaciones =
            Notificacion::where(
                'login',
                $usuario->login
            )
                ->latest('created_at')
                ->limit(10)
                ->get();


        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES NO LEÍDAS
        |--------------------------------------------------------------------------
        */

        $notificacionesNoLeidas =
            Notificacion::where(
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
        | VISTA
        |--------------------------------------------------------------------------
        */

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


    /*
    |--------------------------------------------------------------------------
    | VER DETALLES DE UN TICKET
    |--------------------------------------------------------------------------
    */

    public function verTicket(
        TicketU $ticket
    ) {

        $usuario = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | SEGURIDAD
        |--------------------------------------------------------------------------
        |
        | El ticket debe pertenecer al usuario autenticado.
        |
        | Ahora se compara mediante login.
        |
        */

        if (
            $ticket->login !==
            $usuario->login
        ) {

            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | CARGAR RELACIONES
        |--------------------------------------------------------------------------
        */

        $ticket->load([

            'user.departamento.oficina',

            'tomadoPor',

            'solucion',

            'solucion.solucionadoPor',

        ]);


        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES
        |--------------------------------------------------------------------------
        */

        $notificaciones =
            Notificacion::where(
                'login',
                $usuario->login
            )
                ->latest('created_at')
                ->limit(10)
                ->get();


        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES NO LEÍDAS
        |--------------------------------------------------------------------------
        */

        $notificacionesNoLeidas =
            Notificacion::where(
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
        | AVISOS
        |--------------------------------------------------------------------------
        */

        $avisos =
            Aviso::orderByDesc(
                'created_at'
            )
                ->limit(3)
                ->get();


        /*
        |--------------------------------------------------------------------------
        | VISTA
        |--------------------------------------------------------------------------
        */

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