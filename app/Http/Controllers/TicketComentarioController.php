<?php

namespace App\Http\Controllers;

use App\Models\TicketComentario;
use App\Models\TicketU;
use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TicketComentarioController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | OBTENER COMENTARIOS
    |--------------------------------------------------------------------------
    */

    public function index(TicketU $ticket)
    {
        $comentarios = TicketComentario::query()
            ->where('ticket_id', $ticket->id)
            ->with('usuario')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'success' => true,

            'comentarios' => $comentarios
                ->map(function ($comentario) {
                    return $this->formatearComentario($comentario);
                })
                ->values(),

        ], 200, [
            'Cache-Control' =>
                'no-store, no-cache, must-revalidate, max-age=0',

            'Pragma' => 'no-cache',

            'Expires' => '0',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | GUARDAR COMENTARIO
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        TicketU $ticket
    ) {

        $request->validate([
            'mensaje' => [
                'nullable',
                'string',
                'max:10000',
            ],

            'archivo' => [
                'nullable',
                'file',
                'max:10240',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | DEBE EXISTIR MENSAJE O ARCHIVO
        |--------------------------------------------------------------------------
        */

        if (
            !$request->filled('mensaje') &&
            !$request->hasFile('archivo')
        ) {

            if ($request->expectsJson()) {

                return response()->json([
                    'success' => false,

                    'message' =>
                        'Debes escribir un comentario o adjuntar un archivo.',
                ], 422);
            }

            return back()
                ->with(
                    'error',
                    'Debes escribir un comentario o adjuntar un archivo.'
                )
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | USUARIO AUTENTICADO
        |--------------------------------------------------------------------------
        */

        $usuarioActual = Auth::user();

        if (!$usuarioActual) {
            abort(401);
        }


        /*
        |--------------------------------------------------------------------------
        | LOGIN DEL USUARIO ACTUAL
        |--------------------------------------------------------------------------
        */

        $loginActual = trim(
            (string) $usuarioActual->login
        );


        /*
        |--------------------------------------------------------------------------
        | ROL DEL USUARIO ACTUAL
        |--------------------------------------------------------------------------
        */

        $rolActual = strtolower(
            trim(
                (string) $usuarioActual->role
            )
        );


        Log::info(
            '========== NUEVO COMENTARIO =========='
        );

        Log::info(
            'USUARIO AUTENTICADO',
            [
                'id' =>
                    $usuarioActual->id,

                'login' =>
                    $loginActual,

                'name' =>
                    $usuarioActual->name,

                'role' =>
                    $rolActual,

                'ticket_id' =>
                    $ticket->id,

                'ticket_folio' =>
                    $ticket->folio,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | GUARDAR ARCHIVO
        |--------------------------------------------------------------------------
        */

        $archivo = null;

        if ($request->hasFile('archivo')) {

            $archivo = $request
                ->file('archivo')
                ->store(
                    'comentarios_tickets',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CREAR COMENTARIO
        |--------------------------------------------------------------------------
        */

        $comentario = TicketComentario::create([
            'ticket_id' =>
                $ticket->id,

            'login' =>
                $loginActual,

            'mensaje' =>
                $request->input('mensaje'),

            'archivo' =>
                $archivo,
        ]);


        /*
        |--------------------------------------------------------------------------
        | RECARGAR COMENTARIO
        |--------------------------------------------------------------------------
        */

        $comentario = TicketComentario::query()
            ->whereKey($comentario->id)
            ->with('usuario')
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | CREAR NOTIFICACIONES
        |--------------------------------------------------------------------------
        */

        $this->notificarComentario(
            $ticket,
            $comentario
        );


        /*
        |--------------------------------------------------------------------------
        | RESPUESTA AJAX
        |--------------------------------------------------------------------------
        */

        if ($request->expectsJson()) {

            return response()->json([
                'success' => true,

                'message' =>
                    'Comentario agregado correctamente.',

                'comentario' =>
                    $this->formatearComentario(
                        $comentario
                    ),

            ], 201, [
                'Cache-Control' =>
                    'no-store, no-cache, must-revalidate, max-age=0',

                'Pragma' =>
                    'no-cache',

                'Expires' =>
                    '0',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | RESPUESTA NORMAL
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            'Comentario agregado correctamente.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | NOTIFICAR COMENTARIO
    |--------------------------------------------------------------------------
    */

    private function notificarComentario(
        TicketU $ticket,
        TicketComentario $comentario
    ) {

        /*
        |--------------------------------------------------------------------------
        | LOGIN DEL AUTOR DEL COMENTARIO
        |--------------------------------------------------------------------------
        */

        $loginAutor = trim(
            (string) $comentario->login
        );


        if ($loginAutor === '') {

            Log::error(
                'EL COMENTARIO NO TIENE LOGIN',
                [
                    'comentario_id' =>
                        $comentario->id,

                    'ticket_id' =>
                        $ticket->id,
                ]
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | BUSCAR AUTOR
        |--------------------------------------------------------------------------
        */

        $autor = User::where(
            'login',
            $loginAutor
        )->first();


        if (!$autor) {

            Log::error(
                'NO SE ENCONTRO EL AUTOR DEL COMENTARIO',
                [
                    'login' =>
                        $loginAutor,

                    'comentario_id' =>
                        $comentario->id,

                    'ticket_id' =>
                        $ticket->id,
                ]
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | OBTENER NOMBRE DEL AUTOR
        |--------------------------------------------------------------------------
        |
        | La notificación mostrará:
        |
        | Fernando Reyes comentó...
        |
        | Si no existe nombre, utiliza el login.
        |
        */

        $nombreAutor = trim(
            (string) $autor->name
        );


        if ($nombreAutor === '') {

            $nombreAutor = $loginAutor;
        }


        /*
        |--------------------------------------------------------------------------
        | ROL DEL AUTOR
        |--------------------------------------------------------------------------
        */

        $rolAutor = strtolower(
            trim(
                (string) $autor->role
            )
        );


        /*
        |--------------------------------------------------------------------------
        | MENSAJE DEL COMENTARIO
        |--------------------------------------------------------------------------
        */

        $mensajeComentario = trim(
            (string) $comentario->mensaje
        );


        if ($mensajeComentario === '') {

            $mensajeComentario =
                'Se adjuntó un archivo al comentario.';
        }


        /*
        |--------------------------------------------------------------------------
        | TEXTO DE LA NOTIFICACIÓN
        |--------------------------------------------------------------------------
        */

        $textoNotificacion =
            $nombreAutor .
            ' comentó en el ticket ' .
            $ticket->folio .
            ': ' .
            $mensajeComentario;


        Log::info(
            'DATOS DE LA NOTIFICACIÓN',
            [
                'autor_login' =>
                    $loginAutor,

                'autor_nombre' =>
                    $nombreAutor,

                'autor_rol' =>
                    $rolAutor,

                'ticket_id' =>
                    $ticket->id,

                'mensaje' =>
                    $textoNotificacion,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | CASO 1:
        |
        | USUARIO NORMAL COMENTA
        |
        | Todos los usuarios de tecnologías reciben la notificación.
        |--------------------------------------------------------------------------
        */

        if ($rolAutor !== 'tecnologias') {

            Log::info(
                'COMENTARIO REALIZADO POR USUARIO NORMAL'
            );


            /*
            |--------------------------------------------------------------------------
            | OBTENER TODOS LOS USUARIOS DE TECNOLOGÍAS
            |--------------------------------------------------------------------------
            */

            $tecnologias = User::where(
                'role',
                'tecnologias'
            )
                ->where(
                    'login',
                    '!=',
                    $loginAutor
                )
                ->get();


            Log::info(
                'TECNÓLOGOS ENCONTRADOS',
                [
                    'cantidad' =>
                        $tecnologias->count(),

                    'logins' =>
                        $tecnologias
                            ->pluck('login')
                            ->toArray(),
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | CREAR NOTIFICACIÓN PARA CADA TECNÓLOGO
            |--------------------------------------------------------------------------
            */

            foreach ($tecnologias as $tecnologia) {

                try {

                    $notificacion =
                        Notificacion::create([
                            /*
                            | DESTINATARIO
                            */
                            'login' =>
                                $tecnologia->login,

                            'tipo' =>
                                'comentario',

                            'titulo' =>
                                'Nuevo comentario',

                            /*
                            | AUTOR
                            |
                            | Aquí aparece el nombre de quien comentó.
                            */
                            'mensaje' =>
                                $textoNotificacion,

                            'url' =>
                                route(
                                    'tickettecnologias'
                                ),

                            'leida' =>
                                false,

                            'icono' =>
                                'message-circle',

                            'color' =>
                                'blue',
                        ]);


                    Log::info(
                        'NOTIFICACIÓN CREADA PARA TECNOLOGÍA',
                        [
                            'notificacion_id' =>
                                $notificacion->id,

                            'autor' =>
                                $nombreAutor,

                            'autor_login' =>
                                $loginAutor,

                            'destinatario' =>
                                $tecnologia->login,

                            'ticket_id' =>
                                $ticket->id,
                        ]
                    );

                } catch (\Throwable $e) {

                    Log::error(
                        'ERROR CREANDO NOTIFICACIÓN PARA TECNOLOGÍA',
                        [
                            'autor' =>
                                $loginAutor,

                            'destinatario' =>
                                $tecnologia->login,

                            'ticket_id' =>
                                $ticket->id,

                            'error' =>
                                $e->getMessage(),
                        ]
                    );
                }
            }


            return;
        }


        /*
        |--------------------------------------------------------------------------
        | CASO 2:
        |
        | TECNOLOGÍAS COMENTA
        |
        | Solamente el dueño del ticket recibe la notificación.
        |--------------------------------------------------------------------------
        */

        Log::info(
            'COMENTARIO REALIZADO POR TECNOLOGÍAS'
        );


        /*
        |--------------------------------------------------------------------------
        | OBTENER DUEÑO DEL TICKET
        |--------------------------------------------------------------------------
        */

        $loginUsuarioTicket = trim(
            (string) $ticket->login
        );


        if ($loginUsuarioTicket === '') {

            Log::warning(
                'EL TICKET NO TIENE LOGIN DE USUARIO',
                [
                    'ticket_id' =>
                        $ticket->id,
                ]
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | EVITAR NOTIFICARSE A SÍ MISMO
        |--------------------------------------------------------------------------
        */

        if (
            $loginUsuarioTicket ===
            $loginAutor
        ) {

            Log::warning(
                'EL AUTOR ES EL MISMO DUEÑO DEL TICKET',
                [
                    'login' =>
                        $loginAutor,

                    'ticket_id' =>
                        $ticket->id,
                ]
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | BUSCAR DUEÑO
        |--------------------------------------------------------------------------
        */

        $usuarioTicket = User::where(
            'login',
            $loginUsuarioTicket
        )->first();


        if (!$usuarioTicket) {

            Log::error(
                'NO SE ENCONTRO EL DUEÑO DEL TICKET',
                [
                    'login' =>
                        $loginUsuarioTicket,

                    'ticket_id' =>
                        $ticket->id,
                ]
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | CREAR NOTIFICACIÓN PARA EL DUEÑO
        |--------------------------------------------------------------------------
        */

        try {

            $notificacion =
                Notificacion::create([
                    /*
                    | DESTINATARIO
                    */
                    'login' =>
                        $usuarioTicket->login,

                    'tipo' =>
                        'comentario',

                    'titulo' =>
                        'Nuevo comentario',

                    /*
                    | AUTOR DEL COMENTARIO
                    */
                    'mensaje' =>
                        $textoNotificacion,

                    'url' =>
                        route(
                            'ticketusuario.detalles',
                            [
                                'ticket' =>
                                    $ticket->id,
                            ]
                        ),

                    'leida' =>
                        false,

                    'icono' =>
                        'message-circle',

                    'color' =>
                        'blue',
                ]);


            Log::info(
                'NOTIFICACIÓN CREADA PARA USUARIO',
                [
                    'notificacion_id' =>
                        $notificacion->id,

                    'autor' =>
                        $nombreAutor,

                    'autor_login' =>
                        $loginAutor,

                    'destinatario' =>
                        $usuarioTicket->login,

                    'ticket_id' =>
                        $ticket->id,
                ]
            );

        } catch (\Throwable $e) {

            Log::error(
                'ERROR CREANDO NOTIFICACIÓN PARA USUARIO',
                [
                    'autor' =>
                        $loginAutor,

                    'destinatario' =>
                        $usuarioTicket->login,

                    'ticket_id' =>
                        $ticket->id,

                    'error' =>
                        $e->getMessage(),
                ]
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FORMATEAR COMENTARIO
    |--------------------------------------------------------------------------
    */

    private function formatearComentario(
        TicketComentario $comentario
    ) {

        return [

            'id' =>
                $comentario->id,

            'ticket_id' =>
                $comentario->ticket_id,

            'login' =>
                $comentario->login,

            'mensaje' =>
                $comentario->mensaje,

            'archivo' =>
                $comentario->archivo,

            'url_archivo' =>
                $comentario->archivo
                    ? Storage::url(
                        $comentario->archivo
                    )
                    : null,

            'nombre_archivo' =>
                $comentario->archivo
                    ? basename(
                        $comentario->archivo
                    )
                    : null,

            'extension' =>
                $comentario->archivo
                    ? strtoupper(
                        pathinfo(
                            $comentario->archivo,
                            PATHINFO_EXTENSION
                        )
                    )
                    : null,

            'usuario' =>
                $comentario->usuario
                    ? [

                        'id' =>
                            $comentario->usuario->id,

                        'login' =>
                            $comentario->usuario->login,

                        'name' =>
                            $comentario->usuario->name,

                        'role' =>
                            $comentario->usuario->role
                            ?? 'Usuario',

                        'foto' =>
                            $comentario->usuario->foto
                                ? Storage::url(
                                    $comentario->usuario->foto
                                )
                                : null,

                    ]
                    : null,

            'created_at' =>
                $comentario->created_at
                    ? $comentario
                        ->created_at
                        ->toISOString()
                    : null,

            'fecha' =>
                $comentario->created_at
                    ? $comentario
                        ->created_at
                        ->format(
                            'd M Y h:i A'
                        )
                    : null,
        ];
    }
}