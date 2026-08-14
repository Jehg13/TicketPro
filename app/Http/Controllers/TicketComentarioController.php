<?php

namespace App\Http\Controllers;
use App\Models\TicketComentario;
use App\Models\TicketU;
use Illuminate\Http\Request;

class TicketComentarioController extends Controller
{
     public function index(TicketU $ticket)
    {
        $comentarios = $ticket->historialComentarios()
            ->with('usuario')
            ->get();

        return response()->json([
            'success' => true,

            'comentarios' => $comentarios->map(function ($comentario) {

                return [

                    'id' => $comentario->id,

                    'mensaje' => $comentario->mensaje,

                    'archivo' => $comentario->archivo,

                    'url_archivo' => $comentario->archivo
                        ? Storage::url($comentario->archivo)
                        : null,

                    'nombre_archivo' => $comentario->archivo
                        ? basename($comentario->archivo)
                        : null,

                    'usuario' => [

                        'id' => $comentario->usuario->id,

                        'name' => $comentario->usuario->name,

                        'rol' => $comentario->usuario->rol ?? 'Usuario',

                    ],

                    'fecha' => $comentario->created_at
                        ->format('d M Y h:i A'),

                ];

            }),
        ]);
    }

    public function store(Request $request, TicketU $ticket)
    {
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

        if (
            !$request->filled('mensaje') &&
            !$request->hasFile('archivo')
        ) {

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debes escribir un comentario o adjuntar un archivo.'
                ], 422);
            }

            return back()
                ->with(
                    'error',
                    'Debes escribir un comentario o adjuntar un archivo.'
                )
                ->withInput();
        }

        $archivo = null;

        if ($request->hasFile('archivo')) {

            $archivo = $request->file('archivo')
                ->store(
                    'comentarios_tickets',
                    'public'
                );
        }

        $comentario = TicketComentario::create([

            'ticket_id' => $ticket->id,

            'usuario_id' => auth()->id(),

            'mensaje' => $request->input('mensaje'),

            'archivo' => $archivo,

        ]);

        $comentario->load('usuario');

        if ($request->expectsJson()) {

            return response()->json([

                'success' => true,

                'message' => 'Comentario agregado correctamente.',

                'comentario' => [

                    'id' => $comentario->id,

                    'mensaje' => $comentario->mensaje,

                    'archivo' => $comentario->archivo,

                    'url_archivo' => $comentario->archivo
                        ? asset(
                            'storage/' .
                            $comentario->archivo
                        )
                        : null,

                    'nombre_archivo' => $comentario->archivo
                        ? basename(
                            $comentario->archivo
                        )
                        : null,

                    'usuario' => [

                        'id' => $comentario->usuario->id,

                        'name' => $comentario->usuario->name,

                        'rol' => $comentario->usuario->rol ?? 'Usuario',

                    ],

                    'fecha' => $comentario->created_at
                        ->format('d M Y h:i A'),

                ],

            ]);
        }

        return back()->with(
            'success',
            'Comentario agregado correctamente.'
        );
    }
}