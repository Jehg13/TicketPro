<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\TicketU;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MisTicketsUsuarioController extends Controller
{
    /**
     * ============================================================
     * MIS TICKETS DEL USUARIO
     * ============================================================
     *
     * Devuelve únicamente los tickets creados por el usuario
     * autenticado.
     */
    public function index(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.',
            ], 401);
        }

        $login = trim((string) $usuario->login);

        $buscar = trim(
            $request->input('buscar', '')
        );

        $estado = strtolower(
            trim(
                $request->input(
                    'estado',
                    'todos'
                )
            )
        );

        $estadosPermitidos = [
            'todos',
            'pendiente',
            'en proceso',
            'solucionado',
            'cancelado',
        ];

        if (!in_array(
            $estado,
            $estadosPermitidos,
            true
        )) {
            $estado = 'todos';
        }

        /*
        |--------------------------------------------------------------------------
        | TICKETS DEL USUARIO
        |--------------------------------------------------------------------------
        */

        $ticketsQuery = TicketU::with([
            'user',
            'tomadoPor',
            'historialComentarios.usuario',
            'solucion',
        ])
            ->where(
                'login',
                $login
            );

        /*
        |--------------------------------------------------------------------------
        | FILTRO POR ESTADO
        |--------------------------------------------------------------------------
        */

        if ($estado !== 'todos') {
            $ticketsQuery->where(
                'estado',
                $estado
            );
        }

        /*
        |--------------------------------------------------------------------------
        | BUSCADOR
        |--------------------------------------------------------------------------
        */

        if ($buscar !== '') {

            $ticketsQuery->where(
                function ($q) use ($buscar) {

                    $q->whereRaw(
                        'CAST(folio AS CHAR) LIKE ?',
                        ["%{$buscar}%"]
                    )
                        ->orWhere(
                            'titulo',
                            'LIKE',
                            "%{$buscar}%"
                        )
                        ->orWhere(
                            'tipo_falla',
                            'LIKE',
                            "%{$buscar}%"
                        )
                        ->orWhere(
                            'prioridad',
                            'LIKE',
                            "%{$buscar}%"
                        )
                        ->orWhere(
                            'descripcion',
                            'LIKE',
                            "%{$buscar}%"
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | BÚSQUEDA POR FECHA dd/mm/YYYY
                    |--------------------------------------------------------------------------
                    */

                    try {

                        $fecha = Carbon::createFromFormat(
                            'd/m/Y',
                            $buscar
                        )->format('Y-m-d');

                        $q->orWhereDate(
                            'created_at',
                            $fecha
                        );

                    } catch (\Exception $e) {

                        /*
                        |--------------------------------------------------------------------------
                        | BÚSQUEDA POR FECHA YYYY-mm-dd
                        |--------------------------------------------------------------------------
                        */

                        try {

                            $fecha = Carbon::createFromFormat(
                                'Y-m-d',
                                $buscar
                            )->format('Y-m-d');

                            $q->orWhereDate(
                                'created_at',
                                $fecha
                            );

                        } catch (\Exception $e) {
                            // No es una fecha válida.
                        }
                    }
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINACIÓN
        |--------------------------------------------------------------------------
        */

        $tickets = $ticketsQuery
            ->orderBy(
                'created_at',
                'desc'
            )
            ->paginate(10);

        /*
        |--------------------------------------------------------------------------
        | RESPUESTA
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

            'usuario' => [
                'login' => $usuario->login,
                'name' => $usuario->name,
            ],

            'filtro' => $estado,

            'buscar' => $buscar,

            'tickets' => $tickets->items(),

            'pagination' => [

                'current_page' =>
                    $tickets->currentPage(),

                'last_page' =>
                    $tickets->lastPage(),

                'per_page' =>
                    $tickets->perPage(),

                'total' =>
                    $tickets->total(),

                'from' =>
                    $tickets->firstItem(),

                'to' =>
                    $tickets->lastItem(),
            ],
        ]);
    }


    /**
     * ============================================================
     * DETALLE DE UN TICKET
     * ============================================================
     *
     * El usuario solamente puede consultar sus propios tickets.
     */
    public function show($id)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.',
            ], 401);
        }

        $login = trim(
            (string) $usuario->login
        );

        /*
        |--------------------------------------------------------------------------
        | BUSCAR TICKET
        |--------------------------------------------------------------------------
        |
        | MUY IMPORTANTE:
        | where('login', $login)
        | evita que un usuario pueda consultar tickets ajenos.
        |
        */

        $ticket = TicketU::with([
            'user',
            'tomadoPor',
            'historialComentarios.usuario',
            'solucion',
        ])
            ->where(
                'id',
                $id
            )
            ->where(
                'login',
                $login
            )
            ->first();

        if (!$ticket) {

            return response()->json([
                'success' => false,
                'message' =>
                    'Ticket no encontrado o no tienes permiso para consultarlo.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'ticket' => $ticket,
        ]);
    }


    /**
     * ============================================================
     * NOTIFICACIONES DEL USUARIO
     * ============================================================
     *
     * Devuelve únicamente las notificaciones pertenecientes
     * al usuario autenticado.
     */
    public function notificaciones(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario) {

            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.',
            ], 401);
        }

        $login = trim(
            (string) $usuario->login
        );

        $notificaciones = Notificacion::where(
            'login',
            $login
        )
            ->orderByDesc(
                'created_at'
            )
            ->paginate(10);

        $noLeidas = Notificacion::where(
            'login',
            $login
        )
            ->where(
                'leida',
                false
            )
            ->count();

        return response()->json([

            'success' => true,

            'notificaciones' =>
                $notificaciones->items(),

            'no_leidas' =>
                $noLeidas,

            'pagination' => [

                'current_page' =>
                    $notificaciones->currentPage(),

                'last_page' =>
                    $notificaciones->lastPage(),

                'per_page' =>
                    $notificaciones->perPage(),

                'total' =>
                    $notificaciones->total(),

                'from' =>
                    $notificaciones->firstItem(),

                'to' =>
                    $notificaciones->lastItem(),
            ],
        ]);
    }


    /**
     * ============================================================
     * MARCAR NOTIFICACIÓN COMO LEÍDA
     * ============================================================
     *
     * El usuario solamente puede modificar sus propias
     * notificaciones.
     */
    public function marcarNotificacionLeida($id)
    {
        $usuario = Auth::user();

        if (!$usuario) {

            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.',
            ], 401);
        }

        $login = trim(
            (string) $usuario->login
        );

        $notificacion = Notificacion::where(
            'id',
            $id
        )
            ->where(
                'login',
                $login
            )
            ->first();

        if (!$notificacion) {

            return response()->json([
                'success' => false,
                'message' =>
                    'Notificación no encontrada.',
            ], 404);
        }

        $notificacion->update([
            'leida' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' =>
                'Notificación marcada como leída.',
        ]);
    }


    /**
     * ============================================================
     * MARCAR TODAS LAS NOTIFICACIONES COMO LEÍDAS
     * ============================================================
     */
    public function marcarTodasNotificacionesLeidas()
    {
        $usuario = Auth::user();

        if (!$usuario) {

            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.',
            ], 401);
        }

        $login = trim(
            (string) $usuario->login
        );

        Notificacion::where(
            'login',
            $login
        )
            ->where(
                'leida',
                false
            )
            ->update([
                'leida' => true,
            ]);

        return response()->json([
            'success' => true,
            'message' =>
                'Todas las notificaciones fueron marcadas como leídas.',
        ]);
    }


    /**
     * ============================================================
     * RESUMEN DEL USUARIO
     * ============================================================
     *
     * Esta función es útil para Flutter porque puede obtener
     * en una sola petición:
     *
     * - cantidad de tickets
     * - pendientes
     * - en proceso
     * - solucionados
     * - cancelados
     * - notificaciones no leídas
     */
    public function resumen()
    {
        $usuario = Auth::user();

        if (!$usuario) {

            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.',
            ], 401);
        }

        $login = trim(
            (string) $usuario->login
        );

        $ticketsQuery = TicketU::where(
            'login',
            $login
        );

        $total = (clone $ticketsQuery)
            ->count();

        $pendientes = (clone $ticketsQuery)
            ->where(
                'estado',
                'pendiente'
            )
            ->count();

        $enProceso = (clone $ticketsQuery)
            ->where(
                'estado',
                'en proceso'
            )
            ->count();

        $solucionados = (clone $ticketsQuery)
            ->where(
                'estado',
                'solucionado'
            )
            ->count();

        $cancelados = (clone $ticketsQuery)
            ->where(
                'estado',
                'cancelado'
            )
            ->count();

        $notificacionesNoLeidas =
            Notificacion::where(
                'login',
                $login
            )
                ->where(
                    'leida',
                    false
                )
                ->count();

        return response()->json([

            'success' => true,

            'usuario' => [
                'login' =>
                    $usuario->login,

                'name' =>
                    $usuario->name,

                'email' =>
                    $usuario->email,
            ],

            'tickets' => [

                'total' =>
                    $total,

                'pendientes' =>
                    $pendientes,

                'en_proceso' =>
                    $enProceso,

                'solucionados' =>
                    $solucionados,

                'cancelados' =>
                    $cancelados,
            ],

            'notificaciones' => [

                'no_leidas' =>
                    $notificacionesNoLeidas,
            ],
        ]);
    }
}