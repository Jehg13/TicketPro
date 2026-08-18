<?php

namespace App\Http\Controllers;

use App\Models\TicketComentario;
use App\Models\TicketU;
use Illuminate\Http\Request;
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
        /*
         * IMPORTANTE:
         *
         * Usamos ID ASC.
         *
         * ID menor = comentario más viejo
         * ID mayor = comentario más nuevo
         */
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
         * Validación.
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
         * No permitir comentario completamente vacío.
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
         * Guardar archivo.
         */
        $archivo = null;

        if ($request->hasFile('archivo')) {

            $archivo =
                $request
                    ->file('archivo')
                    ->store(
                        'comentarios_tickets',
                        'public'
                    );
        }


        /*
         * Crear comentario.
         */
        $comentario =
            TicketComentario::create([

                'ticket_id' =>
                    $ticket->id,

                'usuario_id' =>
                    auth()->id(),

                'mensaje' =>
                    $request->input('mensaje'),

                'archivo' =>
                    $archivo,

            ]);


        /*
         * IMPORTANTE:
         *
         * Recargar desde BD.
         *
         * Así nos aseguramos de tener:
         * - ID real
         * - created_at real
         * - usuario real
         */
        $comentario =
            TicketComentario::query()
                ->whereKey($comentario->id)
                ->with('usuario')
                ->firstOrFail();


        /*
         * Respuesta AJAX.
         */
        if ($request->expectsJson()) {

            return response()->json([

                'success' => true,

                'message' =>
                    'Comentario agregado correctamente.',

                /*
                 * Este es EL comentario recién creado.
                 */
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
         * Petición normal.
         */
        return back()->with(
            'success',
            'Comentario agregado correctamente.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORMATEAR COMENTARIO
    |--------------------------------------------------------------------------
    |
    | Utilizamos exactamente la misma estructura tanto para:
    |
    | GET /comentarios
    |
    | como para:
    |
    | POST /comentarios
    |
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
