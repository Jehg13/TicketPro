<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aviso;
use App\Models\Departamento;
use App\Models\User;
use Illuminate\Http\Request;

class AvisosusuarioController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no válida.',
            ], 401);
        }

        $usuarioId = $usuario->id;
        $departamento = $usuario->departamento;
        $departamentoId = $departamento?->id;
        $oficinaId = $departamento?->oficina_id;
        $empresaId = $departamento?->oficina?->empresa_id;

        $buscar = trim($request->input('buscar', ''));

        $filtroTipo = strtolower(
            trim($request->input('tipo', 'todos'))
        );

        if (!$empresaId) {
            return response()->json([
                'success' => true,
                'avisos' => [],
                'total' => 0,
                'filtroTipo' => $filtroTipo,
                'buscar' => $buscar,
            ]);
        }

        $avisos = Aviso::with('publicadoPor')
            ->where('estado', 'activo')
            ->where('fecha_inicio', '<=', now())
            ->get();

        $avisos = $avisos->filter(function ($aviso) use (
            $empresaId,
            $departamentoId,
            $oficinaId,
            $usuarioId
        ) {
            $afecta = $aviso->afecta_a;

            if (is_string($afecta)) {
                $afecta = json_decode($afecta, true);
            }

            if (!is_array($afecta)) {
                return false;
            }

            $tipo = $afecta['tipo'] ?? null;

            if ($tipo === 'todos') {
                if (
                    isset($afecta['empresa_id']) &&
                    (int) $afecta['empresa_id'] !== (int) $empresaId
                ) {
                    return false;
                }

                return true;
            }

            if ($tipo === 'oficina') {
                $avisoOficinaId = $afecta['oficina_id']
                    ?? ($afecta['ids'][0] ?? null);

                if (!$avisoOficinaId || !$oficinaId) {
                    return false;
                }

                return (int) $oficinaId === (int) $avisoOficinaId;
            }

            if ($tipo === 'departamentos') {
                $ids = $afecta['ids'] ?? [];

                if (!is_array($ids) || !$departamentoId) {
                    return false;
                }

                $ids = array_map('intval', $ids);

                return in_array(
                    (int) $departamentoId,
                    $ids,
                    true
                );
            }

            if ($tipo === 'usuarios') {
                $ids = $afecta['ids'] ?? [];

                if (!is_array($ids)) {
                    return false;
                }

                $ids = array_map('intval', $ids);

                return in_array(
                    (int) $usuarioId,
                    $ids,
                    true
                );
            }

            return false;
        });

        if (
            $filtroTipo !== '' &&
            $filtroTipo !== 'todos'
        ) {
            $avisos = $avisos->filter(function ($aviso) use ($filtroTipo) {
                return strtolower(
                    trim($aviso->tipo ?? '')
                ) === $filtroTipo;
            });
        }

        if ($buscar !== '') {
            $texto = mb_strtolower(
                $buscar,
                'UTF-8'
            );

            $avisos = $avisos->filter(function ($aviso) use ($texto) {
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

                return str_contains($titulo, $texto)
                    || str_contains($descripcion, $texto)
                    || str_contains($tipo, $texto)
                    || str_contains($importancia, $texto);
            });
        }

        $avisos = $avisos
            ->map(function ($aviso) {
                $fechaInicio = $aviso->fecha_inicio;

                $aviso->fijado_activo =
                    (bool) $aviso->fijado &&
                    $fechaInicio &&
                    now()->diffInHours($fechaInicio) < 3;

                if (!empty($aviso->archivo)) {
                    $aviso->archivo_url = url(
                        'archivo-aviso/' . ltrim($aviso->archivo, '/')
                    );
                } else {
                    $aviso->archivo_url = null;
                }

                return $aviso;
            })
            ->sort(function ($a, $b) {
                if (
                    $a->fijado_activo &&
                    !$b->fijado_activo
                ) {
                    return -1;
                }

                if (
                    !$a->fijado_activo &&
                    $b->fijado_activo
                ) {
                    return 1;
                }

                return $b->fecha_inicio <=> $a->fecha_inicio;
            })
            ->values();

        foreach ($avisos as $aviso) {
            $afecta = $aviso->afecta_a;

            if (is_string($afecta)) {
                $afecta = json_decode($afecta, true);
            }

            if (!is_array($afecta)) {
                $afecta = [];
            }

            $aviso->afecta_a = $afecta;
            $aviso->afecta_texto = 'Todos los usuarios';
            $aviso->afecta_usuarios = [];
            $aviso->afecta_departamentos = [];

            $tipo = $afecta['tipo'] ?? null;

            if ($tipo === 'todos') {
                $aviso->afecta_texto = 'Toda la empresa';

                continue;
            }

            if ($tipo === 'oficina') {
                $avisoOficinaId = $afecta['oficina_id']
                    ?? ($afecta['ids'][0] ?? null);

                $oficina = $usuario->departamento?->oficina;

                if (
                    $oficina &&
                    $avisoOficinaId &&
                    (int) $oficina->id === (int) $avisoOficinaId
                ) {
                    $aviso->afecta_texto = $oficina->nombre;
                } else {
                    $aviso->afecta_texto = 'Ubicación';
                }

                continue;
            }

            if ($tipo === 'departamentos') {
                $ids = $afecta['ids'] ?? [];

                if (!is_array($ids) || empty($ids)) {
                    $aviso->afecta_texto = 'Departamentos';

                    continue;
                }

                $ids = array_map('intval', $ids);

                $departamentosAviso = Departamento::with('oficina')
                    ->whereIn('id', $ids)
                    ->orderBy('nombre')
                    ->get();

                $aviso->afecta_departamentos =
                    $departamentosAviso
                        ->map(function ($departamento) {
                            return [
                                'id' => $departamento->id,
                                'nombre' => $departamento->nombre,
                                'oficina' => $departamento->oficina?->nombre,
                            ];
                        })
                        ->values()
                        ->toArray();

                $nombres = $departamentosAviso
                    ->map(function ($departamento) {
                        $nombre = $departamento->nombre;

                        if ($departamento->oficina) {
                            $nombre .= ' — ' .
                                $departamento->oficina->nombre;
                        }

                        return $nombre;
                    })
                    ->implode(', ');

                $aviso->afecta_texto =
                    $nombres ?: 'Departamentos';

                continue;
            }

            if ($tipo === 'usuarios') {
                $ids = $afecta['ids'] ?? [];

                if (!is_array($ids) || empty($ids)) {
                    $aviso->afecta_texto =
                        'Usuarios específicos';

                    continue;
                }

                $ids = array_map('intval', $ids);

                $usuariosAviso = User::with(
                    'departamento.oficina'
                )
                    ->whereIn('id', $ids)
                    ->orderBy('name')
                    ->get();

                $aviso->afecta_usuarios =
                    $usuariosAviso
                        ->map(function ($usuarioAviso) {
                            return [
                                'id' => $usuarioAviso->id,
                                'name' => $usuarioAviso->name,
                                'departamento' =>
                                    $usuarioAviso->departamento?->nombre,
                                'oficina' =>
                                    $usuarioAviso->departamento?->oficina?->nombre,
                            ];
                        })
                        ->values()
                        ->toArray();

                $nombres = $usuariosAviso
                    ->map(function ($usuarioAviso) {
                        $nombre = $usuarioAviso->name;

                        if ($usuarioAviso->departamento) {
                            $nombre .= ' — ' .
                                $usuarioAviso->departamento->nombre;

                            if ($usuarioAviso->departamento->oficina) {
                                $nombre .= ' — ' .
                                    $usuarioAviso
                                        ->departamento
                                        ->oficina
                                        ->nombre;
                            }
                        }

                        return $nombre;
                    })
                    ->implode(', ');

                $aviso->afecta_texto =
                    $nombres ?: 'Usuarios específicos';
            }
        }

        return response()->json([
            'success' => true,
            'avisos' => $avisos->values(),
            'total' => $avisos->count(),
            'filtroTipo' => $filtroTipo,
            'buscar' => $buscar,
        ]);
    }
}