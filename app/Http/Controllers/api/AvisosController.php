<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Aviso;
use App\Models\User;
use App\Models\Departamento;
use App\Models\Oficina;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AvisosController extends Controller
{
    public function index(): JsonResponse
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.'
            ], 401);
        }

        $empresaId = $usuario->departamento?->oficina?->empresa_id;

        if (!$empresaId) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo determinar la empresa del usuario actual.'
            ], 422);
        }

        $departamentos = Departamento::with('oficina')
            ->whereHas('oficina', function ($query) use ($empresaId) {
                $query->where('empresa_id', $empresaId);
            })
            ->orderBy('nombre')
            ->get()
            ->unique(function ($departamento) {
                return $departamento->oficina_id . '|' .
                    mb_strtolower(trim($departamento->nombre), 'UTF-8');
            })
            ->values()
            ->map(function ($departamento) {
                return [
                    'id' => $departamento->id,
                    'nombre' => $departamento->nombre,
                    'oficina_id' => $departamento->oficina_id,
                    'oficina' => $departamento->oficina ? [
                        'id' => $departamento->oficina->id,
                        'nombre' => $departamento->oficina->nombre,
                    ] : null,
                ];
            })
            ->values();

        $oficinas = Oficina::where('empresa_id', $empresaId)
            ->whereHas('departamento')
            ->orderBy('nombre')
            ->get()
            ->map(function ($oficina) {
                return [
                    'id' => $oficina->id,
                    'nombre' => $oficina->nombre,
                ];
            })
            ->values();

        $usuarios = User::with('departamento.oficina')
            ->whereHas('departamento.oficina', function ($query) use ($empresaId) {
                $query->where('empresa_id', $empresaId);
            })
            ->orderBy('name')
            ->get()
            ->map(function ($usuario) {
                return [
                    'login' => $usuario->login,
                    'name' => $usuario->name,
                    'email' => $usuario->email,
                    'role' => $usuario->role,
                    'departamento' => $usuario->departamento ? [
                        'id' => $usuario->departamento->id,
                        'nombre' => $usuario->departamento->nombre,
                    ] : null,
                    'oficina' => $usuario->departamento?->oficina ? [
                        'id' => $usuario->departamento->oficina->id,
                        'nombre' => $usuario->departamento->oficina->nombre,
                    ] : null,
                ];
            })
            ->values();

        $avisos = Aviso::with('publicadoPor')
            ->orderByDesc('fijado')
            ->orderByDesc('fecha_inicio')
            ->get()
            ->map(function ($aviso) {
                return $this->formatearAviso($aviso);
            })
            ->values();

        $notificaciones = Notificacion::where('login', $usuario->login)
            ->where('tipo', '!=', 'aviso')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $notificacionesNoLeidas = Notificacion::where('login', $usuario->login)
            ->where('tipo', '!=', 'aviso')
            ->where('leida', false)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'avisos' => $avisos,
                'departamentos' => $departamentos,
                'oficinas' => $oficinas,
                'usuarios' => $usuarios,
                'notificaciones' => $notificaciones,
                'notificaciones_no_leidas' => $notificacionesNoLeidas,
            ]
        ]);
    }

    public function show($id): JsonResponse
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.'
            ], 401);
        }

        $aviso = Aviso::find($id);

        if (!$aviso) {
            return response()->json([
                'success' => false,
                'message' => 'El aviso no existe.'
            ], 404);
        }

        $empresaId = $usuario->departamento?->oficina?->empresa_id;

        if (!$empresaId) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo determinar la empresa del usuario actual.'
            ], 422);
        }

        if (
            !$this->esAdministradorTI($usuario) &&
            !$this->avisoPerteneceAEmpresa($aviso, $empresaId)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes acceso a este aviso.'
            ], 403);
        }

        $aviso->load('publicadoPor');

        return response()->json([
            'success' => true,
            'data' => [
                'aviso' => $this->formatearAviso($aviso),
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.'
            ], 401);
        }

        $empresaId = $usuario->departamento?->oficina?->empresa_id;

        if (!$empresaId) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo determinar la empresa del usuario actual.'
            ], 422);
        }

        $validated = $this->validarDatos($request);

        if ($validated instanceof JsonResponse) {
            return $validated;
        }

        $afectados = $this->obtenerAfectados($request, $empresaId);

        if ($afectados instanceof JsonResponse) {
            return $afectados;
        }

        try {
            DB::beginTransaction();

            $fechaInicio = $request->input('fecha_inicio');
            $horaInicio = $request->input('hora_inicio');

            $validated['fecha_inicio'] = $fechaInicio . ' ' . $horaInicio . ':00';

            $validated['mostrar_notificaciones'] =
                $request->boolean('mostrar_notificaciones');

            $validated['fijado'] =
                $request->boolean('fijado');

            $validated['importancia'] =
                strtolower((string) ($validated['importancia'] ?? 'media'));

            $validated['estado'] =
                strtolower((string) ($request->input('estado', 'activo')));

            $validated['aplica_a'] =
                $request->input('aplica_a');

            $validated['afecta_a'] = $afectados;

            $validated['publicado_por'] = $usuario->login;

            if ($request->hasFile('archivo')) {
                $validated['archivo'] =
                    $request->file('archivo')->store('avisos', 'public');
            } else {
                $validated['archivo'] = null;
            }

            $aviso = Aviso::create($validated);

            if (
                $aviso->estado === 'activo' &&
                (int) $aviso->mostrar_notificaciones === 1
            ) {
                $this->crearNotificacionesAviso(
                    $aviso,
                    $empresaId,
                    $usuario
                );
            }

            DB::commit();

            $aviso->load('publicadoPor');

            return response()->json([
                'success' => true,
                'message' => 'Aviso publicado correctamente.',
                'data' => [
                    'aviso' => $this->formatearAviso($aviso),
                ]
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'No se pudo publicar el aviso.',
                'error' => config('app.debug')
                    ? $e->getMessage()
                    : null,
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.'
            ], 401);
        }

        $aviso = Aviso::find($id);

        if (!$aviso) {
            return response()->json([
                'success' => false,
                'message' => 'El aviso no existe.'
            ], 404);
        }

        $empresaId = $usuario->departamento?->oficina?->empresa_id;

        if (!$empresaId) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo determinar la empresa del usuario actual.'
            ], 422);
        }

        if (
            !$this->esAdministradorTI($usuario) &&
            !$this->avisoPerteneceAEmpresa($aviso, $empresaId)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para modificar este aviso.'
            ], 403);
        }

        $validated = $this->validarDatos($request, true);

        if ($validated instanceof JsonResponse) {
            return $validated;
        }

        $afectados = $this->obtenerAfectados($request, $empresaId);

        if ($afectados instanceof JsonResponse) {
            return $afectados;
        }

        try {
            DB::beginTransaction();

            $fechaInicio = $request->input('fecha_inicio');
            $horaInicio = $request->input('hora_inicio');

            $validated['fecha_inicio'] = $fechaInicio . ' ' . $horaInicio . ':00';

            $validated['mostrar_notificaciones'] =
                $request->boolean('mostrar_notificaciones');

            $validated['fijado'] =
                $request->boolean('fijado');

            $validated['importancia'] =
                strtolower((string) ($validated['importancia'] ?? 'media'));

            $validated['estado'] =
                strtolower((string) ($request->input('estado', 'activo')));

            $validated['aplica_a'] =
                $request->input('aplica_a');

            $validated['afecta_a'] = $afectados;

            if ($request->hasFile('archivo')) {
                if ($aviso->archivo) {
                    Storage::disk('public')->delete($aviso->archivo);
                }

                $validated['archivo'] =
                    $request->file('archivo')->store('avisos', 'public');
            } else {
                unset($validated['archivo']);
            }

            $aviso->update($validated);

            $this->eliminarNotificacionesAviso($aviso);

            $aviso->refresh();

            if (
                $aviso->estado === 'activo' &&
                (int) $aviso->mostrar_notificaciones === 1
            ) {
                $this->crearNotificacionesAviso(
                    $aviso,
                    $empresaId,
                    $usuario
                );
            }

            DB::commit();

            $aviso->load('publicadoPor');

            return response()->json([
                'success' => true,
                'message' => 'Aviso actualizado correctamente.',
                'data' => [
                    'aviso' => $this->formatearAviso($aviso),
                ]
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar el aviso.',
                'error' => config('app.debug')
                    ? $e->getMessage()
                    : null,
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.'
            ], 401);
        }

        $aviso = Aviso::find($id);

        if (!$aviso) {
            return response()->json([
                'success' => false,
                'message' => 'El aviso no existe.'
            ], 404);
        }

        $empresaId = $usuario->departamento?->oficina?->empresa_id;

        if (!$empresaId) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo determinar la empresa del usuario actual.'
            ], 422);
        }

        if (
            !$this->esAdministradorTI($usuario) &&
            !$this->avisoPerteneceAEmpresa($aviso, $empresaId)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para eliminar este aviso.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            $this->eliminarNotificacionesAviso($aviso);

            if ($aviso->archivo) {
                Storage::disk('public')->delete($aviso->archivo);
            }

            $aviso->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Aviso eliminado correctamente.'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar el aviso.',
                'error' => config('app.debug')
                    ? $e->getMessage()
                    : null,
            ], 500);
        }
    }

    private function validarDatos(
        Request $request,
        bool $esActualizacion = false
    ): array|JsonResponse {
        $reglas = [
            'titulo' => [
                'required',
                'string',
                'max:255',
            ],
            'tipo' => [
                'required',
                'in:mantenimiento,incidente,informativo,general',
            ],
            'importancia' => [
                'required',
                'in:critica,alta,media,normal,baja,Baja,Alta,Media,Normal,Critica',
            ],
            'fecha_inicio' => [
                'required',
                'date',
            ],
            'hora_inicio' => [
                'required',
                'date_format:H:i',
            ],
            'aplica_a' => [
                'required',
                'in:todos,oficina,departamento,usuarios',
            ],
            'descripcion' => [
                'required',
                'string',
                'max:1000',
            ],
            'mostrar_notificaciones' => [
                'nullable',
                'boolean',
            ],
            'fijado' => [
                'nullable',
                'boolean',
            ],
            'archivo' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf,mp4',
                'max:20480',
            ],
        ];

        if ($esActualizacion) {
            $reglas['estado'] = [
                'required',
                'in:activo,inactivo,Activo,Inactivo',
            ];
        } else {
            $reglas['estado'] = [
                'nullable',
                'in:activo,inactivo,Activo,Inactivo',
            ];
        }

        try {
            return $request->validate($reglas);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son válidos.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    private function obtenerAfectados(
        Request $request,
        $empresaId
    ): array|JsonResponse {
        $aplicaA = $request->input('aplica_a');

        if ($aplicaA === 'todos') {
            return [
                'tipo' => 'todos',
                'empresa_id' => $empresaId,
            ];
        }

        if ($aplicaA === 'oficina') {
            $oficinaIds = $request->input('afecta_a', []);

            if (!is_array($oficinaIds)) {
                $oficinaIds = [$oficinaIds];
            }

            $oficinaIds = array_values(
                array_unique(
                    array_filter(
                        array_map('intval', $oficinaIds)
                    )
                )
            );

            if (empty($oficinaIds)) {
                return $this->errorAfectados(
                    'Debes seleccionar al menos una oficina.'
                );
            }

            $cantidadValidos = Oficina::whereIn('id', $oficinaIds)
                ->where('empresa_id', $empresaId)
                ->whereHas('departamento')
                ->count();

            if ($cantidadValidos !== count($oficinaIds)) {
                return $this->errorAfectados(
                    'Una o más oficinas no pertenecen a tu empresa o no tienen departamentos.'
                );
            }

            return [
                'tipo' => 'oficinas',
                'ids' => $oficinaIds,
            ];
        }

        if ($aplicaA === 'departamento') {
            $departamentoIds = $request->input('afecta_a', []);

            if (!is_array($departamentoIds)) {
                $departamentoIds = [$departamentoIds];
            }

            $departamentoIds = array_values(
                array_unique(
                    array_filter(
                        array_map('intval', $departamentoIds)
                    )
                )
            );

            if (empty($departamentoIds)) {
                return $this->errorAfectados(
                    'Debes seleccionar al menos un departamento.'
                );
            }

            $cantidadValidos = Departamento::whereIn('id', $departamentoIds)
                ->whereHas('oficina', function ($query) use ($empresaId) {
                    $query->where('empresa_id', $empresaId);
                })
                ->count();

            if ($cantidadValidos !== count($departamentoIds)) {
                return $this->errorAfectados(
                    'Uno o más departamentos no pertenecen a tu empresa.'
                );
            }

            return [
                'tipo' => 'departamentos',
                'ids' => $departamentoIds,
            ];
        }

        if ($aplicaA === 'usuarios') {
            $logins = $request->input('afecta_a', []);

            if (!is_array($logins)) {
                $logins = [$logins];
            }

            $logins = array_values(
                array_unique(
                    array_filter(
                        array_map('trim', $logins)
                    )
                )
            );

            if (empty($logins)) {
                return $this->errorAfectados(
                    'Debes seleccionar al menos un usuario.'
                );
            }

            $cantidadValidos = User::whereIn('login', $logins)
                ->whereHas('departamento.oficina', function ($query) use ($empresaId) {
                    $query->where('empresa_id', $empresaId);
                })
                ->count();

            if ($cantidadValidos !== count($logins)) {
                return $this->errorAfectados(
                    'Uno o más usuarios no pertenecen a tu empresa.'
                );
            }

            return [
                'tipo' => 'usuarios',
                'logins' => $logins,
            ];
        }

        return $this->errorAfectados(
            'El tipo de destinatario no es válido.'
        );
    }

    private function errorAfectados(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => [
                'afecta_a' => [$message],
            ],
        ], 422);
    }

    private function obtenerUsuariosAfectados(
        array $afectaA,
        $empresaId
    ) {
        $tipo = $afectaA['tipo'] ?? null;

        if ($tipo === 'todos') {
            return User::with('departamento.oficina')
                ->whereHas('departamento.oficina', function ($query) use ($empresaId) {
                    $query->where('empresa_id', $empresaId);
                })
                ->get();
        }

        if ($tipo === 'oficinas') {
            return User::with('departamento.oficina')
                ->whereHas('departamento.oficina', function ($query) use (
                    $afectaA,
                    $empresaId
                ) {
                    $query
                        ->whereIn('id', $afectaA['ids'] ?? [])
                        ->where('empresa_id', $empresaId);
                })
                ->get();
        }

        if ($tipo === 'oficina') {
            return User::with('departamento.oficina')
                ->whereHas('departamento.oficina', function ($query) use (
                    $afectaA,
                    $empresaId
                ) {
                    $query
                        ->where('id', $afectaA['oficina_id'] ?? null)
                        ->where('empresa_id', $empresaId);
                })
                ->get();
        }

        if ($tipo === 'departamentos') {
            return User::with('departamento.oficina')
                ->whereHas('departamento', function ($query) use ($afectaA) {
                    $query->whereIn('id', $afectaA['ids'] ?? []);
                })
                ->whereHas('departamento.oficina', function ($query) use ($empresaId) {
                    $query->where('empresa_id', $empresaId);
                })
                ->get();
        }

        if ($tipo === 'usuarios') {
            return User::with('departamento.oficina')
                ->whereIn('login', $afectaA['logins'] ?? [])
                ->whereHas('departamento.oficina', function ($query) use ($empresaId) {
                    $query->where('empresa_id', $empresaId);
                })
                ->get();
        }

        return collect();
    }

    private function crearNotificacionesAviso(
        Aviso $aviso,
        $empresaId,
        User $usuario
    ): void {
        if ($aviso->estado !== 'activo') {
            return;
        }

        if ((int) $aviso->mostrar_notificaciones !== 1) {
            return;
        }

        $afectaA = $aviso->afecta_a;

        if (is_string($afectaA)) {
            $afectaA = json_decode($afectaA, true) ?? [];
        }

        if (!is_array($afectaA)) {
            $afectaA = [];
        }

        $usuariosAfectados = $this->obtenerUsuariosAfectados(
            $afectaA,
            $empresaId
        )
            ->filter(function ($usuarioAfectado) use ($usuario) {
                return $usuarioAfectado->login !== $usuario->login;
            })
            ->filter(function ($usuarioAfectado) {
                return !$this->esTecnologias($usuarioAfectado);
            })
            ->filter(function ($usuarioAfectado) {
                return !$this->esRolExcluido($usuarioAfectado);
            })
            ->unique('login')
            ->values();

        $ahora = now();
        $urlAviso = $this->urlAvisoDual($aviso->id);

        foreach ($usuariosAfectados as $usuarioAfectado) {
            Notificacion::create([
                'login' => $usuarioAfectado->login,
                'tipo' => 'aviso',
                'titulo' => $aviso->titulo,
                'mensaje' => $aviso->descripcion,
                'url' => $urlAviso,
                'leida' => false,
                'icono' => 'megaphone',
                'color' => 'blue',
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ]);
        }
    }

    private function eliminarNotificacionesAviso(Aviso $aviso): void
    {
        Notificacion::where('tipo', 'aviso')
            ->where(function ($query) use ($aviso) {
                $query
                    ->where('url', $this->urlAvisoWeb($aviso->id))
                    ->orWhere('url', $this->urlAvisoApi($aviso->id))
                    ->orWhere('url', $this->urlAvisoDual($aviso->id));
            })
            ->delete();
    }

    private function avisoPerteneceAEmpresa(
        Aviso $aviso,
        $empresaId
    ): bool {
        $afectaA = $aviso->afecta_a;

        if (is_string($afectaA)) {
            $afectaA = json_decode($afectaA, true) ?? [];
        }

        if (!is_array($afectaA)) {
            $afectaA = [];
        }

        if (($afectaA['tipo'] ?? null) === 'todos') {
            return (int) ($afectaA['empresa_id'] ?? 0) === (int) $empresaId;
        }

        $usuarios = $this->obtenerUsuariosAfectados(
            $afectaA,
            $empresaId
        );

        if ($usuarios->isNotEmpty()) {
            return true;
        }

        $publicadoPor = $aviso->publicado_por;

        if (!$publicadoPor) {
            return false;
        }

        return User::where('login', $publicadoPor)
            ->whereHas('departamento.oficina', function ($query) use ($empresaId) {
                $query->where('empresa_id', $empresaId);
            })
            ->exists();
    }

    private function formatearAviso(Aviso $aviso): array
    {
        $archivoUrl = null;

        if ($aviso->archivo) {
            $archivoUrl = url('/archivo/' . ltrim($aviso->archivo, '/'));
        }

        $afectaA = $aviso->afecta_a;

        if (is_string($afectaA)) {
            $afectaA = json_decode($afectaA, true) ?? [];
        }

        if (!is_array($afectaA)) {
            $afectaA = [];
        }

        return [
            'id' => $aviso->id,
            'titulo' => $aviso->titulo,
            'tipo' => $aviso->tipo,
            'importancia' => $aviso->importancia,
            'fecha_inicio' => $aviso->fecha_inicio,
            'hora_inicio' => $aviso->fecha_inicio
                ? Carbon::parse($aviso->fecha_inicio)->format('H:i')
                : null,
            'aplica_a' => $this->obtenerAplicaA($afectaA),
            'afecta_a' => $afectaA,
            'descripcion' => $aviso->descripcion,
            'mostrar_notificaciones' => (bool) $aviso->mostrar_notificaciones,
            'fijado' => (bool) $aviso->fijado,
            'estado' => $aviso->estado,
            'archivo' => $aviso->archivo,
            'archivo_url' => $archivoUrl,
            'publicado_por' => $aviso->publicado_por,
            'publicado_por_usuario' => $aviso->publicadoPor ? [
                'login' => $aviso->publicadoPor->login,
                'name' => $aviso->publicadoPor->name,
                'email' => $aviso->publicadoPor->email,
            ] : null,
            'created_at' => $aviso->created_at,
            'updated_at' => $aviso->updated_at,
        ];
    }

    private function obtenerAplicaA(?array $afectaA): ?string
    {
        return match ($afectaA['tipo'] ?? null) {
            'todos' => 'todos',
            'oficinas', 'oficina' => 'oficina',
            'departamentos' => 'departamento',
            'usuarios' => 'usuarios',
            default => null,
        };
    }

    private function urlAvisoWeb(int $id): string
    {
        return url('/dashboard/avisos?id=' . $id);
    }

    private function urlAvisoApi(int $id): string
    {
        return url('/api/avisos/' . $id);
    }

    private function urlAvisoDual(int $id): string
    {
        return json_encode(
            [
                'web' => $this->urlAvisoWeb($id),
                'mobile' => $this->urlAvisoApi($id),
            ],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }

    private function esTecnologias(User $usuario): bool
    {
        $nombreDepartamento = $usuario->departamento?->nombre;

        if (!$nombreDepartamento) {
            return false;
        }

        $nombreDepartamento = mb_strtolower(
            trim($nombreDepartamento),
            'UTF-8'
        );

        $nombreDepartamento = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ü'],
            ['a', 'e', 'i', 'o', 'u', 'u'],
            $nombreDepartamento
        );

        return in_array(
            $nombreDepartamento,
            ['tecnologias', 'tecnologia'],
            true
        );
    }

    private function esRolExcluido(User $usuario): bool
    {
        $rol = mb_strtolower(
            trim($usuario->role ?? ''),
            'UTF-8'
        );

        $rol = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ü'],
            ['a', 'e', 'i', 'o', 'u', 'u'],
            $rol
        );

        return in_array(
            $rol,
            ['gerente ti', 'soporte tecnico'],
            true
        );
    }

    private function esAdministradorTI(User $usuario): bool
    {
        $rol = trim((string) ($usuario->role ?? ''));
        $privAdmin = strtoupper(
            trim((string) ($usuario->priv_admin ?? ''))
        );

        return in_array(
            $rol,
            ['Gerente Ti', 'Soporte Tecnico'],
            true
        ) && $privAdmin === 'Y';
    }
}