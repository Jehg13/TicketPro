<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\SolicitudCambio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CambiosController extends Controller
{
    private function puedeAdministrarCambios(): bool
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return false;
        }

        return $usuario->role === 'Gerente Ti'
            && $usuario->priv_admin === 'Y';
    }

    private function verificarPermisos(): void
    {
        if (!$this->puedeAdministrarCambios()) {
            abort(403, 'No tienes permisos para administrar las solicitudes de cambio.');
        }
    }

    public function index(Request $request)
    {
        $this->verificarPermisos();

        $usuario = Auth::user();

        $porPagina = (int) $request->input('por_pagina', 10);

        if ($porPagina < 1) {
            $porPagina = 10;
        }

        if ($porPagina > 100) {
            $porPagina = 100;
        }

        $query = SolicitudCambio::with([
            'usuario.departamento.oficina',
            'revisor',
        ])->latest();

        if ($request->filled('estado')) {
            $query->where(
                'estado',
                $request->input('estado')
            );
        }

        if ($request->filled('buscar')) {
            $buscar = trim($request->input('buscar'));

            if ($buscar !== '') {
                $query->where(function ($q) use ($buscar) {
                    $q->where('campo', 'LIKE', "%{$buscar}%")
                        ->orWhere('valor_actual', 'LIKE', "%{$buscar}%")
                        ->orWhere('nuevo_valor', 'LIKE', "%{$buscar}%")
                        ->orWhere('motivo', 'LIKE', "%{$buscar}%")
                        ->orWhere('comentario_admin', 'LIKE', "%{$buscar}%")
                        ->orWhereHas('usuario', function ($userQuery) use ($buscar) {
                            $userQuery
                                ->where('name', 'LIKE', "%{$buscar}%")
                                ->orWhere('email', 'LIKE', "%{$buscar}%")
                                ->orWhere('login', 'LIKE', "%{$buscar}%");
                        });
                });
            }
        }

        $solicitudes = $query->paginate(
            $porPagina,
            ['*'],
            'page',
            (int) $request->input('page', 1)
        );

        $solicitudesFormateadas = collect($solicitudes->items())
            ->map(function (SolicitudCambio $solicitud) {
                return $this->formatearSolicitud($solicitud);
            })
            ->values();

        $total = SolicitudCambio::count();

        $pendientes = SolicitudCambio::where(
            'estado',
            'pendiente'
        )->count();

        $aprobadas = SolicitudCambio::where(
            'estado',
            'aprobada'
        )->count();

        $rechazadas = SolicitudCambio::where(
            'estado',
            'rechazada'
        )->count();

        $notificaciones = Notificacion::where(
            'login',
            $usuario->login
        )
            ->where('tipo', '!=', 'aviso')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $notificacionesNoLeidas = Notificacion::where(
            'login',
            $usuario->login
        )
            ->where('tipo', '!=', 'aviso')
            ->where('leida', false)
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Solicitudes obtenidas correctamente.',
            'data' => [
                'solicitudes' => $solicitudesFormateadas,
                'pagination' => [
                    'current_page' => $solicitudes->currentPage(),
                    'last_page' => $solicitudes->lastPage(),
                    'per_page' => $solicitudes->perPage(),
                    'total' => $solicitudes->total(),
                    'from' => $solicitudes->firstItem(),
                    'to' => $solicitudes->lastItem(),
                ],
                'estadisticas' => [
                    'total' => $total,
                    'pendientes' => $pendientes,
                    'aprobadas' => $aprobadas,
                    'rechazadas' => $rechazadas,
                ],
                'notificaciones' => $notificaciones,
                'notificaciones_no_leidas' => $notificacionesNoLeidas,
            ],
        ]);
    }

    public function show(int $id)
    {
        $this->verificarPermisos();

        $solicitud = SolicitudCambio::with([
            'usuario.departamento.oficina',
            'revisor',
        ])->find($id);

        if (!$solicitud) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró la solicitud de cambio.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Solicitud obtenida correctamente.',
            'data' => [
                'solicitud' => $this->formatearSolicitud($solicitud),
            ],
        ]);
    }

    public function aprobar(Request $request, int $id)
    {
        $this->verificarPermisos();

        $solicitud = SolicitudCambio::find($id);

        if (!$solicitud) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró la solicitud de cambio.',
            ], 404);
        }

        if ($solicitud->estado !== 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Esta solicitud ya fue revisada.',
            ], 422);
        }

        $datos = $request->validate([
            'comentario_admin' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $usuarioRevisor = Auth::user();

        DB::transaction(function () use (
            $solicitud,
            $datos,
            $usuarioRevisor
        ) {
            $solicitud->estado = 'aprobada';
            $solicitud->comentario_admin =
                $datos['comentario_admin'] ?? null;
            $solicitud->revisado_por =
                $usuarioRevisor->login;
            $solicitud->revisado_at =
                now();

            $solicitud->save();

            $this->notificarResultado(
                $solicitud,
                'aprobada'
            );
        });

        $solicitud->refresh();

        $solicitud->load([
            'usuario.departamento.oficina',
            'revisor',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'La solicitud fue aprobada correctamente.',
            'data' => [
                'solicitud' => $this->formatearSolicitud($solicitud),
            ],
        ]);
    }

    public function rechazar(Request $request, int $id)
    {
        $this->verificarPermisos();

        $solicitud = SolicitudCambio::find($id);

        if (!$solicitud) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró la solicitud de cambio.',
            ], 404);
        }

        if ($solicitud->estado !== 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Esta solicitud ya fue revisada.',
            ], 422);
        }

        $datos = $request->validate([
            'comentario_admin' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        $usuarioRevisor = Auth::user();

        DB::transaction(function () use (
            $solicitud,
            $datos,
            $usuarioRevisor
        ) {
            $solicitud->estado = 'rechazada';
            $solicitud->comentario_admin =
                $datos['comentario_admin'];
            $solicitud->revisado_por =
                $usuarioRevisor->login;
            $solicitud->revisado_at =
                now();

            $solicitud->save();

            $this->notificarResultado(
                $solicitud,
                'rechazada'
            );
        });

        $solicitud->refresh();

        $solicitud->load([
            'usuario.departamento.oficina',
            'revisor',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'La solicitud fue rechazada correctamente.',
            'data' => [
                'solicitud' => $this->formatearSolicitud($solicitud),
            ],
        ]);
    }

    private function notificarResultado(
        SolicitudCambio $solicitud,
        string $resultado
    ): void {
        $login = $solicitud->login;

        if (!$login) {
            return;
        }

        if ($resultado === 'aprobada') {
            $titulo = 'Solicitud de cambio aprobada';

            $mensaje =
                'Tu solicitud de cambio fue aprobada por el área de Tecnologías.';

            $icono = 'check-circle';

            $color = 'green';
        } else {
            $titulo = 'Solicitud de cambio rechazada';

            $mensaje =
                'Tu solicitud de cambio fue rechazada por el área de Tecnologías.';

            if (!empty($solicitud->comentario_admin)) {
                $mensaje .=
                    ' Motivo: ' .
                    $solicitud->comentario_admin;
            }

            $icono = 'x-circle';

            $color = 'red';
        }

        Notificacion::create([
            'login' => $login,
            'tipo' => 'solicitud_cambio',
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'url' => null,
            'leida' => false,
            'icono' => $icono,
            'color' => $color,
        ]);
    }

    private function formatearSolicitud(
        SolicitudCambio $solicitud
    ): array {
        $usuario = $solicitud->usuario;
        $revisor = $solicitud->revisor;

        $departamento = $usuario?->departamento;
        $oficina = $departamento?->oficina;

        return [
            'id' => $solicitud->id,

            'login' => $solicitud->login,

            'campo' => $solicitud->campo,

            'valor_actual' => $solicitud->valor_actual,

            'nuevo_valor' => $solicitud->nuevo_valor,

            'motivo' => $solicitud->motivo,

            'estado' => $solicitud->estado,

            'comentario_admin' =>
                $solicitud->comentario_admin,

            'revisado_por' =>
                $solicitud->revisado_por,

            'revisado_at' =>
                $solicitud->revisado_at,

            'created_at' =>
                $solicitud->created_at,

            'updated_at' =>
                $solicitud->updated_at,

            'usuario' => $usuario ? [
                'login' => $usuario->login,
                'name' => $usuario->name,
                'email' => $usuario->email,
                'phone' => $usuario->phone,
                'picture' => $usuario->picture
                    ? asset('storage/' . $usuario->picture)
                    : asset('images/user.png'),
                'role' => $usuario->role,
                'priv_admin' => $usuario->priv_admin,
                'departamento' =>
                    $departamento?->nombre,
                'oficina' =>
                    $oficina?->nombre,
            ] : null,

            'revisor' => $revisor ? [
                'login' => $revisor->login,
                'name' => $revisor->name,
                'email' => $revisor->email,
            ] : null,
        ];
    }
}
