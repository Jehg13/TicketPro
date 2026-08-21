<?php

namespace App\Http\Controllers;

use App\Models\Aviso;
use App\Models\Departamento;
use App\Models\User;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class AvisosusuarioController extends Controller
{
    public function create(Request $request)
    {
        $usuario = Auth::user();

        $usuarioId = $usuario->id;
        $departamentoId = $usuario->departamento?->id;
        $empresaId = $usuario->departamento?->oficina?->empresa_id;

        $buscar = trim(
            $request->input('buscar', '')
        );

        $filtroTipo = strtolower(
            trim(
                $request->input('tipo', 'todos')
            )
        );

        $notificaciones = Notificacion::where(
            'login',
            $usuario->login
        )
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $notificacionesNoLeidas = Notificacion::where(
            'login',
            $usuario->login
        )
            ->where('leida', false)
            ->count();

        if (!$empresaId) {
            if ($request->expectsJson()) {
                return response()->json([
                    'avisos' => [],
                    'total' => 0,
                    'filtroTipo' => $filtroTipo,
                    'buscar' => $buscar,
                ]);
            }

            return view('user.avisos', [
                'avisos' => collect(),
                'avisosTodos' => collect(),
                'usuarios' => collect(),
                'departamentos' => collect(),
                'notificaciones' => $notificaciones,
                'notificacionesNoLeidas' => $notificacionesNoLeidas,
                'buscar' => $buscar,
                'filtroTipo' => $filtroTipo,
            ]);
        }

        $avisos = Aviso::with('publicadoPor')
            ->where(
                'fecha_inicio',
                '<=',
                now()
            )
            ->where(function ($query) {
                $query
                    ->whereNull('fecha_fin')
                    ->orWhere(
                        'fecha_fin',
                        '>=',
                        now()
                    );
            })
            ->get();

        $avisos = $avisos->filter(
            function ($aviso) use (
                $empresaId,
                $departamentoId,
                $usuarioId
            ) {
                $afecta = $aviso->afecta_a;

                if (is_string($afecta)) {
                    $afecta = json_decode(
                        $afecta,
                        true
                    );
                }

                if (!is_array($afecta)) {
                    return false;
                }

                $tipo = $afecta['tipo'] ?? null;

                if ($tipo === 'todos') {
                    if (
                        isset($afecta['empresa_id']) &&
                        (int) $afecta['empresa_id'] !==
                        (int) $empresaId
                    ) {
                        return false;
                    }

                    return true;
                }

                if ($tipo === 'departamentos') {
                    $departamentoIds =
                        $afecta['ids'] ?? [];

                    if (
                        !is_array($departamentoIds) ||
                        !$departamentoId
                    ) {
                        return false;
                    }

                    $departamentoIds = array_map(
                        'intval',
                        $departamentoIds
                    );

                    return in_array(
                        (int) $departamentoId,
                        $departamentoIds,
                        true
                    );
                }

                if ($tipo === 'usuarios') {
                    $usuarioIds =
                        $afecta['ids'] ?? [];

                    if (!is_array($usuarioIds)) {
                        return false;
                    }

                    $usuarioIds = array_map(
                        'intval',
                        $usuarioIds
                    );

                    return in_array(
                        (int) $usuarioId,
                        $usuarioIds,
                        true
                    );
                }

                return false;
            }
        );

        $avisos = $avisos->filter(
            function ($aviso) use ($filtroTipo) {
                if (
                    $filtroTipo === '' ||
                    $filtroTipo === 'todos'
                ) {
                    return true;
                }

                return strtolower(
                    $aviso->tipo ?? ''
                ) === $filtroTipo;
            }
        );

        $avisos = $avisos->filter(
            function ($aviso) use ($buscar) {
                if ($buscar === '') {
                    return true;
                }

                $texto = mb_strtolower(
                    $buscar,
                    'UTF-8'
                );

                $titulo = mb_strtolower(
                    $aviso->titulo ?? '',
                    'UTF-8'
                );

                $descripcion = mb_strtolower(
                    $aviso->descripcion ?? '',
                    'UTF-8'
                );

                $tipo = mb_strtolower(
                    $aviso->tipo ?? '',
                    'UTF-8'
                );

                $importancia = mb_strtolower(
                    $aviso->importancia ?? '',
                    'UTF-8'
                );

                return
                    str_contains($titulo, $texto) ||
                    str_contains($descripcion, $texto) ||
                    str_contains($tipo, $texto) ||
                    str_contains($importancia, $texto);
            }
        );

        $avisos = $avisos
            ->sortByDesc('fecha_inicio')
            ->sortByDesc('fijado')
            ->values();

        $avisosTodos = $avisos->values();

        foreach ($avisosTodos as $aviso) {
            $afecta = $aviso->afecta_a;

            if (is_string($afecta)) {
                $afecta = json_decode(
                    $afecta,
                    true
                );
            }

            if (!is_array($afecta)) {
                $afecta = [];
            }

            $aviso->afecta_a = $afecta;
            $aviso->afecta_texto =
                'Todos los usuarios';

            $aviso->afecta_usuarios = [];
            $aviso->afecta_departamentos = [];

            $tipo = $afecta['tipo'] ?? null;

            if ($tipo === 'todos') {
                $aviso->afecta_texto =
                    'Toda la empresa';

                continue;
            }

            if ($tipo === 'departamentos') {
                $ids = $afecta['ids'] ?? [];

                if (
                    !is_array($ids) ||
                    empty($ids)
                ) {
                    $aviso->afecta_texto =
                        'Departamentos';

                    continue;
                }

                $ids = array_map(
                    'intval',
                    $ids
                );

                $departamentosAviso =
                    Departamento::with('oficina')
                        ->whereIn('id', $ids)
                        ->orderBy('nombre')
                        ->get();

                $aviso->afecta_departamentos =
                    $departamentosAviso
                        ->map(
                            function ($departamento) {
                                return [
                                    'id' =>
                                        $departamento->id,

                                    'nombre' =>
                                        $departamento->nombre,

                                    'oficina' =>
                                        $departamento
                                            ->oficina
                                            ?->nombre,
                                ];
                            }
                        )
                        ->values()
                        ->toArray();

                $nombres =
                    $departamentosAviso
                        ->map(
                            function ($departamento) {
                                $nombre =
                                    $departamento->nombre;

                                if (
                                    $departamento->oficina
                                ) {
                                    $nombre .=
                                        ' — ' .
                                        $departamento
                                            ->oficina
                                            ->nombre;
                                }

                                return $nombre;
                            }
                        )
                        ->implode(', ');

                $aviso->afecta_texto =
                    $nombres
                    ?: 'Departamentos';

                continue;
            }

            if ($tipo === 'usuarios') {
                $ids = $afecta['ids'] ?? [];

                if (
                    !is_array($ids) ||
                    empty($ids)
                ) {
                    $aviso->afecta_texto =
                        'Usuarios específicos';

                    continue;
                }

                $ids = array_map(
                    'intval',
                    $ids
                );

                $usuariosAviso =
                    User::with(
                        'departamento.oficina'
                    )
                        ->whereIn('id', $ids)
                        ->orderBy('name')
                        ->get();

                $aviso->afecta_usuarios =
                    $usuariosAviso
                        ->map(
                            function ($usuario) {
                                return [
                                    'id' =>
                                        $usuario->id,

                                    'name' =>
                                        $usuario->name,

                                    'departamento' =>
                                        $usuario
                                            ->departamento
                                            ?->nombre,

                                    'oficina' =>
                                        $usuario
                                            ->departamento
                                            ?->oficina
                                            ?->nombre,
                                ];
                            }
                        )
                        ->values()
                        ->toArray();

                $nombres =
                    $usuariosAviso
                        ->map(
                            function ($usuario) {
                                $nombre =
                                    $usuario->name;

                                if (
                                    $usuario->departamento
                                ) {
                                    $nombre .=
                                        ' — ' .
                                        $usuario
                                            ->departamento
                                            ->nombre;

                                    if (
                                        $usuario
                                            ->departamento
                                            ->oficina
                                    ) {
                                        $nombre .=
                                            ' — ' .
                                            $usuario
                                                ->departamento
                                                ->oficina
                                                ->nombre;
                                    }
                                }

                                return $nombre;
                            }
                        )
                        ->implode(', ');

                $aviso->afecta_texto =
                    $nombres
                    ?: 'Usuarios específicos';
            }
        }

        $totalAvisos =
            $avisosTodos->count();

        if ($request->expectsJson()) {
            return response()->json([
                'avisos' =>
                    $avisosTodos->values(),

                'total' =>
                    $totalAvisos,

                'filtroTipo' =>
                    $filtroTipo,

                'buscar' =>
                    $buscar,
            ]);
        }

        $paginaActual =
            LengthAwarePaginator::resolveCurrentPage();

        $porPagina = 5;

        $avisosPagina =
            $avisosTodos
                ->forPage(
                    $paginaActual,
                    $porPagina
                )
                ->values();

        $avisosPaginados =
            new LengthAwarePaginator(
                $avisosPagina,
                $totalAvisos,
                $porPagina,
                $paginaActual,
                [
                    'path' =>
                        $request->url(),

                    'query' =>
                        $request->query(),
                ]
            );

        $usuarios =
            User::with('departamento')
                ->orderBy('name')
                ->get()
                ->map(
                    function ($usuario) {
                        return [
                            'id' =>
                                $usuario->id,

                            'name' =>
                                $usuario->name,

                            'departamento' =>
                                $usuario
                                    ->departamento
                                    ?->nombre
                                    ?? 'Sin departamento',
                        ];
                    }
                )
                ->values();

        $departamentos =
            Departamento::orderBy('nombre')
                ->get()
                ->map(
                    function ($departamento) {
                        return [
                            'id' =>
                                $departamento->id,

                            'nombre' =>
                                $departamento->nombre,
                        ];
                    }
                )
                ->values();

        return view(
            'user.avisos',
            [
                'avisos' =>
                    $avisosPaginados,

                'avisosTodos' =>
                    $avisosTodos,

                'usuarios' =>
                    $usuarios,

                'departamentos' =>
                    $departamentos,

                'notificaciones' =>
                    $notificaciones,

                'notificacionesNoLeidas' =>
                    $notificacionesNoLeidas,

                'buscar' =>
                    $buscar,

                'filtroTipo' =>
                    $filtroTipo,
            ]
        );
    }
}
