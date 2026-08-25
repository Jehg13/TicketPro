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
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

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

        if (
            !$request->filled('mensaje') &&
            !$request->hasFile('archivo')
        ) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debes escribir un comentario o adjuntar un archivo.',
                ], 422);
            }

            return back()
                ->with(
                    'error',
                    'Debes escribir un comentario o adjuntar un archivo.'
                )
                ->withInput();
        }

        $usuarioActual = Auth::user();

        if (!$usuarioActual) {
            abort(401);
        }

        $loginActual = trim(
            (string) $usuarioActual->login
        );

        $rolActual = $this->normalizarRol(
            $usuarioActual->role
        );

        Log::info(
            '========== NUEVO COMENTARIO =========='
        );

        Log::info(
            'USUARIO AUTENTICADO',
            [
                'id' => $usuarioActual->id,
                'login' => $loginActual,
                'name' => $usuarioActual->name,
                'role' => $usuarioActual->role,
                'role_normalizado' => $rolActual,
                'priv_admin' => $usuarioActual->priv_admin,
                'ticket_id' => $ticket->id,
                'ticket_folio' => $ticket->folio,
            ]
        );

        $archivo = null;

        if ($request->hasFile('archivo')) {
            $archivo = $request
                ->file('archivo')
                ->store(
                    'comentarios_tickets',
                    'public'
                );
        }

        $comentario = TicketComentario::create([
            'ticket_id' => $ticket->id,
            'login' => $loginActual,
            'mensaje' => $request->input('mensaje'),
            'archivo' => $archivo,
        ]);

        $comentario = TicketComentario::query()
            ->whereKey($comentario->id)
            ->with('usuario')
            ->firstOrFail();

        $this->notificarComentario(
            $ticket,
            $comentario
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comentario agregado correctamente.',
                'comentario' => $this->formatearComentario(
                    $comentario
                ),
            ], 201, [
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }

        return back()->with(
            'success',
            'Comentario agregado correctamente.'
        );
    }

    private function notificarComentario(
        TicketU $ticket,
        TicketComentario $comentario
    ) {
        $loginAutor = trim(
            (string) $comentario->login
        );

        if ($loginAutor === '') {
            Log::error(
                'EL COMENTARIO NO TIENE LOGIN',
                [
                    'comentario_id' => $comentario->id,
                    'ticket_id' => $ticket->id,
                ]
            );

            return;
        }

        $autor = User::where(
            'login',
            $loginAutor
        )->first();

        if (!$autor) {
            Log::error(
                'NO SE ENCONTRO EL AUTOR DEL COMENTARIO',
                [
                    'login' => $loginAutor,
                    'comentario_id' => $comentario->id,
                    'ticket_id' => $ticket->id,
                ]
            );

            return;
        }

        $nombreAutor = trim(
            (string) $autor->name
        );

        if ($nombreAutor === '') {
            $nombreAutor = $loginAutor;
        }

        $rolAutor = $this->normalizarRol(
            $autor->role
        );

        $esAdministrador = in_array(
            $rolAutor,
            [
                'gerente ti',
                'soporte tecnico',
            ],
            true
        ) && $autor->priv_admin === 'Y';

        $mensajeComentario = trim(
            (string) $comentario->mensaje
        );

        if ($mensajeComentario === '') {
            $mensajeComentario =
                'Se adjuntó un archivo al comentario.';
        }

        $textoNotificacion =
            $nombreAutor .
            ' comentó en el ticket ' .
            $ticket->folio .
            ': ' .
            $mensajeComentario;

        Log::info(
            'DATOS DE LA NOTIFICACIÓN',
            [
                'autor_login' => $loginAutor,
                'autor_nombre' => $nombreAutor,
                'autor_rol' => $rolAutor,
                'autor_priv_admin' => $autor->priv_admin,
                'es_administrador' => $esAdministrador,
                'ticket_id' => $ticket->id,
                'mensaje' => $textoNotificacion,
            ]
        );

        if (!$esAdministrador) {
            Log::info(
                'COMENTARIO REALIZADO POR USUARIO NORMAL'
            );

            $administradores = User::query()
                ->where('priv_admin', 'Y')
                ->where(function ($query) {
                    $query
                        ->whereRaw(
                            'LOWER(TRIM(role)) = ?',
                            ['gerente ti']
                        )
                        ->orWhereRaw(
                            'LOWER(TRIM(role)) IN (?, ?)',
                            [
                                'soporte tecnico',
                                'soporte técnico',
                            ]
                        );
                })
                ->where(
                    'login',
                    '!=',
                    $loginAutor
                )
                ->get();

            Log::info(
                'ADMINISTRADORES ENCONTRADOS',
                [
                    'cantidad' => $administradores->count(),
                    'usuarios' => $administradores
                        ->map(function ($usuario) {
                            return [
                                'id' => $usuario->id,
                                'login' => $usuario->login,
                                'name' => $usuario->name,
                                'role' => $usuario->role,
                                'priv_admin' => $usuario->priv_admin,
                            ];
                        })
                        ->toArray(),
                ]
            );

            foreach ($administradores as $administrador) {
                try {
                    $notificacion = Notificacion::create([
                        'login' => $administrador->login,
                        'tipo' => 'comentario',
                        'titulo' => 'Nuevo comentario',
                        'mensaje' => $textoNotificacion,
                        'url' => '/tecnologias/tickets/',
                        'leida' => false,
                        'icono' => 'message-circle',
                        'color' => 'blue',
                    ]);

                    Log::info(
                        'NOTIFICACIÓN CREADA CORRECTAMENTE',
                        [
                            'notificacion_id' => $notificacion->id,
                            'autor' => $nombreAutor,
                            'autor_login' => $loginAutor,
                            'destinatario' => $administrador->login,
                            'destinatario_nombre' => $administrador->name,
                            'destinatario_role' => $administrador->role,
                            'destinatario_priv_admin' => $administrador->priv_admin,
                            'ticket_id' => $ticket->id,
                        ]
                    );
                } catch (\Throwable $e) {
                    Log::error(
                        'ERROR CREANDO NOTIFICACIÓN PARA ADMINISTRADOR',
                        [
                            'autor' => $loginAutor,
                            'destinatario' => $administrador->login,
                            'ticket_id' => $ticket->id,
                            'error' => $e->getMessage(),
                            'archivo' => $e->getFile(),
                            'linea' => $e->getLine(),
                        ]
                    );
                }
            }

            return;
        }

        Log::info(
            'COMENTARIO REALIZADO POR GERENTE TI / SOPORTE TECNICO'
        );

        $loginUsuarioTicket = trim(
            (string) $ticket->login
        );

        if ($loginUsuarioTicket === '') {
            Log::warning(
                'EL TICKET NO TIENE LOGIN DE USUARIO',
                [
                    'ticket_id' => $ticket->id,
                ]
            );

            return;
        }

        if ($loginUsuarioTicket === $loginAutor) {
            Log::warning(
                'EL AUTOR ES EL MISMO DUEÑO DEL TICKET',
                [
                    'login' => $loginAutor,
                    'ticket_id' => $ticket->id,
                ]
            );

            return;
        }

        $usuarioTicket = User::where(
            'login',
            $loginUsuarioTicket
        )->first();

        if (!$usuarioTicket) {
            Log::error(
                'NO SE ENCONTRO EL DUEÑO DEL TICKET',
                [
                    'login' => $loginUsuarioTicket,
                    'ticket_id' => $ticket->id,
                ]
            );

            return;
        }

        try {
            $notificacion = Notificacion::create([
                'login' => $usuarioTicket->login,
                'tipo' => 'comentario',
                'titulo' => 'Nuevo comentario',
                'mensaje' => $textoNotificacion,
                'url' => '/dashboard/mistickets',
                'leida' => false,
                'icono' => 'message-circle',
                'color' => 'blue',
            ]);

            Log::info(
                'NOTIFICACIÓN CREADA PARA DUEÑO DEL TICKET',
                [
                    'notificacion_id' => $notificacion->id,
                    'autor' => $nombreAutor,
                    'autor_login' => $loginAutor,
                    'autor_role' => $autor->role,
                    'destinatario' => $usuarioTicket->login,
                    'destinatario_nombre' => $usuarioTicket->name,
                    'ticket_id' => $ticket->id,
                ]
            );
        } catch (\Throwable $e) {
            Log::error(
                'ERROR CREANDO NOTIFICACIÓN PARA DUEÑO',
                [
                    'autor' => $loginAutor,
                    'destinatario' => $usuarioTicket->login,
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage(),
                    'archivo' => $e->getFile(),
                    'linea' => $e->getLine(),
                ]
            );
        }
    }

    private function normalizarRol($rol): string
    {
        $rol = mb_strtolower(
            trim(
                (string) $rol
            )
        );

        $rol = strtr(
            $rol,
            [
                'á' => 'a',
                'é' => 'e',
                'í' => 'i',
                'ó' => 'o',
                'ú' => 'u',
                'ü' => 'u',
            ]
        );

        return $rol;
    }

    private function formatearComentario(
        TicketComentario $comentario
    ) {
        return [
            'id' => $comentario->id,

            'ticket_id' => $comentario->ticket_id,

            'login' => $comentario->login,

            'mensaje' => $comentario->mensaje,

            'archivo' => $comentario->archivo,

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

                        'picture' =>
                            $comentario->usuario->picture
                                ? Storage::url(
                                    $comentario->usuario->picture
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