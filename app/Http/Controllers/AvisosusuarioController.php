<?php

namespace App\Http\Controllers;

use App\Models\Aviso;
use App\Models\Departamento;
use App\Models\User;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class AvisosusuarioController extends Controller
{
    public function create(Request $request)
    {
        Log::info('================ INICIO AvisosusuarioController::create ================');

        try {

            // ============================================================
            // USUARIO AUTENTICADO
            // ============================================================

            $usuario = Auth::user();

            Log::info('Usuario autenticado', [
                'usuario' => $usuario?->toArray(),
                'login' => $usuario?->login,
                'name' => $usuario?->name,
                'role' => $usuario?->role,
            ]);

            if (!$usuario) {
                Log::warning('No existe usuario autenticado.');

                return redirect()->route('login');
            }

            // ============================================================
            // DATOS DEL USUARIO
            // ============================================================

            $usuarioLogin = $usuario->login;

            $departamento = $usuario->departamento;
            $oficina = $departamento?->oficina;

            $departamentoId = $departamento?->id;
            $empresaId = $oficina?->empresa_id;
            $oficinaId = $departamento?->oficina_id;

            Log::info('Relaciones del usuario', [
                'login' => $usuarioLogin,

                'departamento_id' => $departamentoId,
                'departamento_nombre' => $departamento?->nombre,

                'oficina_id' => $oficinaId,
                'oficina_nombre' => $oficina?->nombre,

                'empresa_id' => $empresaId,
            ]);

            // ============================================================
            // FILTROS
            // ============================================================

            $buscar = trim($request->input('buscar', ''));

            $filtroTipo = strtolower(
                trim($request->input('tipo', 'todos'))
            );

            Log::info('Filtros recibidos', [
                'buscar' => $buscar,
                'tipo' => $filtroTipo,
                'expects_json' => $request->expectsJson(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);

            // ============================================================
            // NOTIFICACIONES
            // ============================================================

            $notificaciones = Notificacion::where(
                'login',
                $usuarioLogin
            )
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();

            $notificacionesNoLeidas = Notificacion::where(
                'login',
                $usuarioLogin
            )
                ->where('leida', false)
                ->count();

            Log::info('Notificaciones obtenidas', [
                'total_notificaciones' => $notificaciones->count(),
                'no_leidas' => $notificacionesNoLeidas,
            ]);

            // ============================================================
            // VALIDAR EMPRESA
            // ============================================================

            if (!$empresaId) {

                Log::warning('El usuario no tiene empresa asociada.', [
                    'login' => $usuarioLogin,
                    'departamento_id' => $departamentoId,
                    'oficina_id' => $oficinaId,
                    'empresa_id' => $empresaId,
                ]);

                if ($request->expectsJson()) {

                    Log::info('Retornando JSON porque no existe empresa.');

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

            // ============================================================
            // OBTENER AVISOS
            // ============================================================

            $avisos = Aviso::with('publicadoPor')
                ->where('estado', 'activo')
                ->where('fecha_inicio', '<=', now())
                ->get();

            Log::info('Avisos obtenidos desde BD', [
                'total' => $avisos->count(),
                'empresa_usuario' => $empresaId,
            ]);

            // ============================================================
            // FILTRAR SEGÚN AFECTA_A
            // ============================================================

            $avisos = $avisos->filter(function ($aviso) use (
                $empresaId,
                $departamentoId,
                $usuarioLogin,
                $usuario
            ) {

                $afectaOriginal = $aviso->afecta_a;

                Log::info('Analizando aviso', [
                    'aviso_id' => $aviso->id,
                    'titulo' => $aviso->titulo,
                    'afecta_a_original' => $afectaOriginal,
                ]);

                $afecta = $afectaOriginal;

                if (is_string($afecta)) {
                    $afecta = json_decode($afecta, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {

                        Log::error('Error decodificando afecta_a', [
                            'aviso_id' => $aviso->id,
                            'json_error' => json_last_error_msg(),
                            'afecta_a' => $afectaOriginal,
                        ]);
                    }
                }

                if (!is_array($afecta)) {

                    Log::warning('afecta_a no es un array válido', [
                        'aviso_id' => $aviso->id,
                        'afecta_a' => $afecta,
                    ]);

                    return false;
                }

                $tipo = $afecta['tipo'] ?? null;

                Log::info('Tipo de destinatario del aviso', [
                    'aviso_id' => $aviso->id,
                    'tipo' => $tipo,
                    'afecta' => $afecta,
                ]);

                // ========================================================
                // TODOS
                // ========================================================

                if ($tipo === 'todos') {

                    if (
                        isset($afecta['empresa_id']) &&
                        (int) $afecta['empresa_id'] !== (int) $empresaId
                    ) {

                        Log::info('Aviso descartado por empresa', [
                            'aviso_id' => $aviso->id,
                            'empresa_aviso' => $afecta['empresa_id'],
                            'empresa_usuario' => $empresaId,
                        ]);

                        return false;
                    }

                    Log::info('Aviso aceptado: todos', [
                        'aviso_id' => $aviso->id,
                        'empresa_id' => $empresaId,
                    ]);

                    return true;
                }

                // ========================================================
                // OFICINA
                // ========================================================

                if ($tipo === 'oficina') {

                    $oficinaId = $afecta['oficina_id']
                        ?? ($afecta['ids'][0] ?? null);

                    Log::info('Validando aviso por oficina', [
                        'aviso_id' => $aviso->id,
                        'oficina_aviso' => $oficinaId,
                        'oficina_usuario' => $usuario->departamento?->oficina_id,
                    ]);

                    if (!$oficinaId) {

                        Log::warning('Aviso de oficina sin oficina_id', [
                            'aviso_id' => $aviso->id,
                            'afecta' => $afecta,
                        ]);

                        return false;
                    }

                    $resultado =
                        (int) $usuario->departamento?->oficina_id ===
                        (int) $oficinaId;

                    Log::info('Resultado filtro oficina', [
                        'aviso_id' => $aviso->id,
                        'resultado' => $resultado,
                    ]);

                    return $resultado;
                }

                // ========================================================
                // DEPARTAMENTOS
                // ========================================================

                if ($tipo === 'departamentos') {

                    $departamentoIds = $afecta['ids'] ?? [];

                    Log::info('Validando aviso por departamentos', [
                        'aviso_id' => $aviso->id,
                        'departamentos_aviso' => $departamentoIds,
                        'departamento_usuario' => $departamentoId,
                    ]);

                    if (
                        !is_array($departamentoIds) ||
                        !$departamentoId
                    ) {

                        Log::warning('Aviso de departamentos inválido', [
                            'aviso_id' => $aviso->id,
                            'departamentos' => $departamentoIds,
                            'departamento_usuario' => $departamentoId,
                        ]);

                        return false;
                    }

                    $departamentoIds = array_map(
                        'intval',
                        $departamentoIds
                    );

                    $resultado = in_array(
                        (int) $departamentoId,
                        $departamentoIds,
                        true
                    );

                    Log::info('Resultado filtro departamentos', [
                        'aviso_id' => $aviso->id,
                        'resultado' => $resultado,
                    ]);

                    return $resultado;
                }

                // ========================================================
                // USUARIOS
                // ========================================================

                if ($tipo === 'usuarios') {

                    $usuarioLogins = $afecta['logins']
                        ?? ($afecta['ids'] ?? []);

                    Log::info('Validando aviso por usuarios', [
                        'aviso_id' => $aviso->id,
                        'usuarios_aviso' => $usuarioLogins,
                        'usuario_actual' => $usuarioLogin,
                    ]);

                    if (!is_array($usuarioLogins)) {

                        Log::warning('Lista de usuarios inválida', [
                            'aviso_id' => $aviso->id,
                            'usuarios' => $usuarioLogins,
                        ]);

                        return false;
                    }

                    $usuarioLogins = array_map(
                        'strval',
                        $usuarioLogins
                    );

                    $resultado = in_array(
                        (string) $usuarioLogin,
                        $usuarioLogins,
                        true
                    );

                    Log::info('Resultado filtro usuarios', [
                        'aviso_id' => $aviso->id,
                        'resultado' => $resultado,
                    ]);

                    return $resultado;
                }

                // ========================================================
                // TIPO DESCONOCIDO
                // ========================================================

                Log::warning('Tipo de afecta_a desconocido', [
                    'aviso_id' => $aviso->id,
                    'tipo' => $tipo,
                    'afecta' => $afecta,
                ]);

                return false;
            });

            Log::info('Avisos después del filtro de destinatarios', [
                'total' => $avisos->count(),
                'ids' => $avisos->pluck('id')->values()->toArray(),
            ]);

            // ============================================================
            // FILTRO POR TIPO
            // ============================================================

            $avisos = $avisos->filter(function ($aviso) use ($filtroTipo) {

                if (
                    $filtroTipo === '' ||
                    $filtroTipo === 'todos'
                ) {
                    return true;
                }

                $tipoAviso = strtolower(
                    trim($aviso->tipo ?? '')
                );

                $resultado = $tipoAviso === $filtroTipo;

                Log::info('Filtro por tipo', [
                    'aviso_id' => $aviso->id,
                    'tipo_aviso' => $tipoAviso,
                    'tipo_buscado' => $filtroTipo,
                    'resultado' => $resultado,
                ]);

                return $resultado;
            });

            // ============================================================
            // FILTRO DE BÚSQUEDA
            // ============================================================

            $avisos = $avisos->filter(function ($aviso) use ($buscar) {

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

                $resultado =
                    str_contains($titulo, $texto) ||
                    str_contains($descripcion, $texto) ||
                    str_contains($tipo, $texto) ||
                    str_contains($importancia, $texto);

                Log::info('Filtro de búsqueda', [
                    'aviso_id' => $aviso->id,
                    'buscar' => $buscar,
                    'resultado' => $resultado,
                ]);

                return $resultado;
            });

            // ============================================================
            // FIJADOS + ORDEN
            // ============================================================

            $avisos = $avisos
                ->map(function ($aviso) {

                    $aviso->fijado_activo =
                        (bool) $aviso->fijado &&
                        $aviso->fecha_inicio &&
                        now()->diffInHours($aviso->fecha_inicio) < 3;

                    Log::info('Estado fijado del aviso', [
                        'aviso_id' => $aviso->id,
                        'fijado' => $aviso->fijado,
                        'fecha_inicio' => $aviso->fecha_inicio,
                        'fijado_activo' => $aviso->fijado_activo,
                    ]);

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

            $avisosTodos = $avisos->values();

            Log::info('Avisos finales antes de procesar destinatarios', [
                'total' => $avisosTodos->count(),
                'ids' => $avisosTodos->pluck('id')->toArray(),
            ]);

            // ============================================================
            // PREPARAR INFORMACIÓN DE DESTINATARIOS
            // ============================================================

            foreach ($avisosTodos as $aviso) {

                Log::info('Procesando información visual del aviso', [
                    'aviso_id' => $aviso->id,
                    'titulo' => $aviso->titulo,
                ]);

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

                Log::info('Preparando destinatarios', [
                    'aviso_id' => $aviso->id,
                    'tipo' => $tipo,
                    'afecta' => $afecta,
                ]);

                // ========================================================
                // TODOS
                // ========================================================

                if ($tipo === 'todos') {

                    $aviso->afecta_texto =
                        'Toda la empresa';

                    continue;
                }

                // ========================================================
                // OFICINA
                // ========================================================

                if ($tipo === 'oficina') {

                    $oficinaId = $afecta['oficina_id']
                        ?? ($afecta['ids'][0] ?? null);

                    $oficina = $usuario->departamento?->oficina;

                    if (
                        $oficina &&
                        $oficinaId &&
                        (int) $oficina->id === (int) $oficinaId
                    ) {

                        $aviso->afecta_texto =
                            $oficina->nombre;

                    } else {

                        $aviso->afecta_texto =
                            'Ubicación';
                    }

                    continue;
                }

                // ========================================================
                // DEPARTAMENTOS
                // ========================================================

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

                    Log::info('Departamentos encontrados para aviso', [
                        'aviso_id' => $aviso->id,
                        'ids' => $ids,
                        'encontrados' => $departamentosAviso->pluck('id')->toArray(),
                    ]);

                    $aviso->afecta_departamentos =
                        $departamentosAviso
                            ->map(function ($departamento) {
                                return [
                                    'id' => $departamento->id,
                                    'nombre' => $departamento->nombre,
                                    'oficina' =>
                                        $departamento->oficina?->nombre,
                                ];
                            })
                            ->values()
                            ->toArray();

                    $nombres = $departamentosAviso
                        ->map(function ($departamento) {

                            $nombre =
                                $departamento->nombre;

                            if ($departamento->oficina) {

                                $nombre .=
                                    ' — ' .
                                    $departamento->oficina->nombre;
                            }

                            return $nombre;
                        })
                        ->implode(', ');

                    $aviso->afecta_texto =
                        $nombres ?: 'Departamentos';

                    continue;
                }

                // ========================================================
                // USUARIOS
                // ========================================================

                if ($tipo === 'usuarios') {

                    $logins = $afecta['logins']
                        ?? ($afecta['ids'] ?? []);

                    if (
                        !is_array($logins) ||
                        empty($logins)
                    ) {

                        $aviso->afecta_texto =
                            'Usuarios específicos';

                        continue;
                    }

                    $logins = array_map(
                        'strval',
                        $logins
                    );

                    $usuariosAviso =
                        User::with(
                            'departamento.oficina'
                        )
                            ->whereIn('login', $logins)
                            ->orderBy('name')
                            ->get();

                    Log::info('Usuarios encontrados para aviso', [
                        'aviso_id' => $aviso->id,
                        'logins_buscados' => $logins,
                        'logins_encontrados' => $usuariosAviso
                            ->pluck('login')
                            ->toArray(),
                    ]);

                    $aviso->afecta_usuarios =
                        $usuariosAviso
                            ->map(function ($usuarioAviso) {
                                return [
                                    'login' => $usuarioAviso->login,
                                    'name' => $usuarioAviso->name,
                                    'departamento' =>
                                        $usuarioAviso->departamento?->nombre,
                                    'oficina' =>
                                        $usuarioAviso
                                            ->departamento
                                            ?->oficina
                                            ?->nombre,
                                ];
                            })
                            ->values()
                            ->toArray();

                    $nombres = $usuariosAviso
                        ->map(function ($usuarioAviso) {

                            $nombre =
                                $usuarioAviso->name;

                            if ($usuarioAviso->departamento) {

                                $nombre .=
                                    ' — ' .
                                    $usuarioAviso
                                        ->departamento
                                        ->nombre;

                                if (
                                    $usuarioAviso
                                        ->departamento
                                        ->oficina
                                ) {

                                    $nombre .=
                                        ' — ' .
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

            // ============================================================
            // TOTAL
            // ============================================================

            $totalAvisos = $avisosTodos->count();

            Log::info('Total final de avisos', [
                'total' => $totalAvisos,
                'ids' => $avisosTodos->pluck('id')->toArray(),
            ]);

            // ============================================================
            // RESPUESTA JSON
            // ============================================================

            if ($request->expectsJson()) {

                Log::info('Retornando respuesta JSON', [
                    'total' => $totalAvisos,
                ]);

                return response()->json([
                    'avisos' => $avisosTodos->values(),
                    'total' => $totalAvisos,
                    'filtroTipo' => $filtroTipo,
                    'buscar' => $buscar,
                ]);
            }

            // ============================================================
            // PAGINACIÓN
            // ============================================================

            $paginaActual =
                LengthAwarePaginator::resolveCurrentPage();

            $porPagina = 5;

            $avisosPagina = $avisosTodos
                ->forPage(
                    $paginaActual,
                    $porPagina
                )
                ->values();

            Log::info('Paginación', [
                'pagina_actual' => $paginaActual,
                'por_pagina' => $porPagina,
                'avisos_en_pagina' => $avisosPagina->count(),
                'total' => $totalAvisos,
            ]);

            $avisosPaginados = new LengthAwarePaginator(
                $avisosPagina,
                $totalAvisos,
                $porPagina,
                $paginaActual,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );

            // ============================================================
            // USUARIOS
            // ============================================================

            $usuarios = User::with('departamento')
                ->orderBy('name')
                ->get()
                ->map(function ($usuario) {
                    return [
                        'login' => $usuario->login,
                        'name' => $usuario->name,
                        'departamento' =>
                            $usuario->departamento?->nombre
                            ?? 'Sin departamento',
                    ];
                })
                ->values();

            Log::info('Usuarios cargados', [
                'total' => $usuarios->count(),
            ]);

            // ============================================================
            // DEPARTAMENTOS
            // ============================================================

            $departamentos = Departamento::orderBy('nombre')
                ->get()
                ->map(function ($departamento) {
                    return [
                        'id' => $departamento->id,
                        'nombre' => $departamento->nombre,
                    ];
                })
                ->values();

            Log::info('Departamentos cargados', [
                'total' => $departamentos->count(),
            ]);

            // ============================================================
            // VISTA
            // ============================================================

            Log::info('Retornando vista user.avisos');

            Log::info('================ FIN AvisosusuarioController::create ================');

            return view('user.avisos', [
                'avisos' => $avisosPaginados,
                'avisosTodos' => $avisosTodos,
                'usuarios' => $usuarios,
                'departamentos' => $departamentos,
                'notificaciones' => $notificaciones,
                'notificacionesNoLeidas' => $notificacionesNoLeidas,
                'buscar' => $buscar,
                'filtroTipo' => $filtroTipo,
            ]);

        } catch (\Throwable $e) {

            // ============================================================
            // ERROR GENERAL
            // ============================================================

            Log::error('ERROR EN AvisosusuarioController::create', [
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}