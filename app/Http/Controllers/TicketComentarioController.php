<?php

namespace App\Http\Controllers;

use App\Models\TicketComentario;
use App\Models\TicketU;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

            'comentarios' => $comentarios->map(
                fn ($comentario) =>
                    $this->formatearComentario($comentario)
            )->values(),

        ], 200, [

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
    | GUARDAR COMENTARIO
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        TicketU $ticket
    ) {

        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN
        |--------------------------------------------------------------------------
        */

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
        | EVITAR COMENTARIO VACÍO
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

            'usuario_id' =>
                Auth::id(),

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
        |
        | Se notifica a los demás involucrados en el ticket.
        |
        | Usuario comenta:
        |   -> Notificar al técnico.
        |
        | Técnico comenta:
        |   -> Notificar al usuario dueño del ticket.
        |
        | Nunca se notifica al usuario que acaba de comentar.
        |
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
        | PETICIÓN NORMAL
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

        $usuarioActualId = Auth::id();

        /*
        |--------------------------------------------------------------------------
        | RECARGAR TICKET
        |--------------------------------------------------------------------------
        */

        $ticket->loadMissing([
            'user',
            'tomadoPor',
        ]);


        /*
        |--------------------------------------------------------------------------
        | USUARIOS QUE PUEDEN RECIBIR LA NOTIFICACIÓN
        |--------------------------------------------------------------------------
        */

        $usuariosNotificar = collect();


        /*
        |--------------------------------------------------------------------------
        | DUEÑO DEL TICKET
        |--------------------------------------------------------------------------
        */

        if (
            !is_null($ticket->user_id) &&
            (int) $ticket->user_id !== (int) $usuarioActualId
        ) {

            $usuariosNotificar->push([
                'id' => $ticket->user_id,

                'url' => route(
                    'ticketusuario'
                ),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | TÉCNICO QUE TOMÓ EL TICKET
        |--------------------------------------------------------------------------
        */

        if (
            !is_null($ticket->tomado_por) &&
            (int) $ticket->tomado_por !== (int) $usuarioActualId
        ) {

            $usuariosNotificar->push([
                'id' => $ticket->tomado_por,

                'url' => route(
                    'tickettecnologias'
                ),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | ELIMINAR DUPLICADOS
        |--------------------------------------------------------------------------
        */

        $usuariosNotificar = $usuariosNotificar
            ->unique('id')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | MENSAJE DE NOTIFICACIÓN
        |--------------------------------------------------------------------------
        */

        $nombreUsuario =
            $comentario->usuario?->name
            ?? 'Un usuario';


        $mensaje =
            $comentario->mensaje
            ? $comentario->mensaje
            : 'Se adjuntó un archivo al comentario.';


        /*
        |--------------------------------------------------------------------------
        | CREAR NOTIFICACIONES
        |--------------------------------------------------------------------------
        */

        foreach (
            $usuariosNotificar
            as $destinatario
        ) {

            /*
             * Seguridad adicional:
             *
             * Nunca crear una notificación
             * para quien hizo el comentario.
             */

            if (
                (int) $destinatario['id'] ===
                (int) $usuarioActualId
            ) {
                continue;
            }


            Notificacion::create([

                'user_id' =>
                    $destinatario['id'],

                'tipo' =>
                    'comentario',

                'titulo' =>
                    'Nuevo comentario en ticket ' .
                    $ticket->folio,

                'mensaje' =>
                    $nombreUsuario .
                    ': ' .
                    $mensaje,

                'url' =>
                    $destinatario['url'],

                'leida' =>
                    false,

                /*
                 * Si tu tabla tiene estas columnas,
                 * puedes dejarlas.
                 *
                 * Si NO existen en tu tabla,
                 * elimina estas dos líneas.
                 */

                'icono' =>
                    'message-circle',

                'color' =>
                    'blue',
            ]);
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

            'usuario_id' =>
                $comentario->usuario_id,

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
                    ? pathinfo(
                        $comentario->archivo,
                        PATHINFO_EXTENSION
                    )
                    : null,

            'usuario' =>
                $comentario->usuario
                    ? [

                        'id' =>
                            $comentario->usuario->id,

                        'name' =>
                            $comentario->usuario->name,

                        'rol' =>
                            $comentario->usuario->rol ??
                            'Usuario',

                        'foto' =>
                            $comentario->usuario->foto
                                ? Storage::url(
                                    $comentario->usuario->foto
                                )
                                : null,

                    ]
                    : null,

            /*
             * Fecha original de BD.
             */

            'created_at' =>
                $comentario->created_at
                    ? $comentario->created_at
                        ->toISOString()
                    : null,

            /*
             * Fecha para mostrar.
             */

            'fecha' =>
                $comentario->created_at
                    ? $comentario->created_at
                        ->format(
                            'd M Y h:i A'
                        )
                    : null,
        ];
    }
}