<?php

namespace App\Http\Controllers;

use App\Models\Aviso;
use App\Models\User;
use App\Models\Departamento;
use App\Models\Oficina;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AvisosController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        $empresaId = $usuario->departamento?->oficina?->empresa_id;

        if (!$empresaId) {
            return back()->withErrors([
                'empresa' => 'No se pudo determinar la empresa del usuario actual.'
            ]);
        }

        $departamentos = Departamento::with('oficina')
            ->whereHas('oficina', function ($query) use ($empresaId) {
                $query->where('empresa_id', $empresaId);
            })
            ->orderBy('nombre')
            ->get()
            ->unique(function ($departamento) {
                return $departamento->oficina_id . '|' .
                    mb_strtolower(
                        trim($departamento->nombre),
                        'UTF-8'
                    );
            })
            ->values();

        $oficinas = Oficina::where(
            'empresa_id',
            $empresaId
        )
            ->whereHas('departamento')
            ->orderBy('nombre')
            ->get();

        $usuarios = User::with(
            'departamento.oficina'
        )
            ->whereHas(
                'departamento.oficina',
                function ($query) use ($empresaId) {
                    $query->where(
                        'empresa_id',
                        $empresaId
                    );
                }
            )
            ->orderBy('name')
            ->get();

        $avisos = Aviso::with('publicadoPor')
            ->orderByDesc('fijado')
            ->orderByDesc('fecha_inicio')
            ->get();

        $notificaciones = Notificacion::where(
            'login',
            $usuario->login
        )
            ->where(
                'tipo',
                '!=',
                'aviso'
            )
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $notificacionesNoLeidas = Notificacion::where(
            'login',
            $usuario->login
        )
            ->where(
                'tipo',
                '!=',
                'aviso'
            )
            ->where(
                'leida',
                false
            )
            ->count();

        return view(
            'admin.avisos',
            compact(
                'avisos',
                'departamentos',
                'oficinas',
                'usuarios',
                'notificaciones',
                'notificacionesNoLeidas'
            )
        );
    }

    public function store(Request $request)
    {
        $usuario = Auth::user();

        $empresaId = $usuario->departamento?->oficina?->empresa_id;

        if (!$empresaId) {
            return back()
                ->withInput()
                ->withErrors([
                    'aplica_a' =>
                        'No se pudo determinar la empresa del usuario actual.'
                ]);
        }

        $validated = $request->validate([
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
                'in:critica,alta,media,normal',
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

            'estado' => [
                'nullable',
                'in:activo,inactivo',
            ],

            'archivo' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf,mp4',
                'max:20480',
            ],
        ]);

        $validated['fecha_inicio'] =
            $request->fecha_inicio .
            ' ' .
            $request->hora_inicio .
            ':00';

        $validated['mostrar_notificaciones'] =
            $request->boolean('mostrar_notificaciones');

        $validated['fijado'] =
            $request->boolean('fijado');

        $validated['estado'] =
            $request->input('estado', 'activo');

        $validated['archivo'] =
            $request->hasFile('archivo')
                ? $request
                    ->file('archivo')
                    ->store(
                        'avisos',
                        'public'
                    )
                : null;

        $validated['afecta_a'] =
            $this->obtenerAfectados(
                $request,
                $empresaId
            );

        $validated['publicado_por'] =
            $usuario->login;

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

        return redirect()
            ->route('avisostecnologias')
            ->with(
                'success',
                'Aviso publicado correctamente.'
            );
    }

    public function update(
        Request $request,
        Aviso $aviso
    ) {
        $usuario = Auth::user();

        $empresaId =
            $usuario
                ->departamento
                ?->oficina
                ?->empresa_id;

        if (!$empresaId) {
            return back()
                ->withInput()
                ->withErrors([
                    'aplica_a' =>
                        'No se pudo determinar la empresa del usuario actual.'
                ]);
        }

        $validated = $request->validate([
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
                'in:critica,alta,media,normal',
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

            'estado' => [
                'required',
                'in:activo,inactivo',
            ],

            'archivo' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf,mp4',
                'max:20480',
            ],
        ]);

        $validated['fecha_inicio'] =
            $request->fecha_inicio .
            ' ' .
            $request->hora_inicio .
            ':00';

        $validated['mostrar_notificaciones'] =
            $request->boolean('mostrar_notificaciones');

        $validated['fijado'] =
            $request->boolean('fijado');

        $validated['estado'] =
            $request->input('estado');

        if ($request->hasFile('archivo')) {
            if ($aviso->archivo) {
                Storage::disk('public')->delete(
                    $aviso->archivo
                );
            }

            $validated['archivo'] =
                $request
                    ->file('archivo')
                    ->store(
                        'avisos',
                        'public'
                    );
        } else {
            unset($validated['archivo']);
        }

        $validated['afecta_a'] =
            $this->obtenerAfectados(
                $request,
                $empresaId
            );

        $aviso->update($validated);

        Notificacion::where(
            'tipo',
            'aviso'
        )
            ->where(
                'url',
                route(
                    'avisosusuario',
                    $aviso->id
                )
            )
            ->delete();

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

        return redirect()
            ->route('avisostecnologias')
            ->with(
                'success',
                'Aviso actualizado correctamente.'
            );
    }

    public function destroy(
        Aviso $aviso
    ) {
        Notificacion::where(
            'tipo',
            'aviso'
        )
            ->where(
                'url',
                route(
                    'avisosusuario',
                    $aviso->id
                )
            )
            ->delete();

        if ($aviso->archivo) {
            Storage::disk('public')->delete(
                $aviso->archivo
            );
        }

        $aviso->delete();

        return redirect()
            ->route('avisostecnologias')
            ->with(
                'success',
                'Aviso eliminado correctamente.'
            );
    }

    private function crearNotificacionesAviso(
        Aviso $aviso,
        $empresaId,
        User $usuario
    ) {
        if ($aviso->estado !== 'activo') {
            return;
        }

        if ((int) $aviso->mostrar_notificaciones !== 1) {
            return;
        }

        $usuariosAfectados =
            $this->obtenerUsuariosAfectados(
                $aviso->afecta_a,
                $empresaId
            )
                ->filter(function (
                    $usuarioAfectado
                ) use ($usuario) {
                    return $usuarioAfectado->login !==
                        $usuario->login;
                })
                ->filter(function (
                    $usuarioAfectado
                ) {
                    return !$this->esTecnologias(
                        $usuarioAfectado
                    );
                })
                ->filter(function (
                    $usuarioAfectado
                ) {
                    return !$this->esRolExcluido(
                        $usuarioAfectado
                    );
                })
                ->unique('login')
                ->values();

        $ahora = now();

        foreach (
            $usuariosAfectados
            as $usuarioAfectado
        ) {
            Notificacion::create([
                'login' =>
                    $usuarioAfectado->login,

                'tipo' =>
                    'aviso',

                'titulo' =>
                    $aviso->titulo,

                'mensaje' =>
                    $aviso->descripcion,

                'url' =>
                    route(
                        'avisosusuario',
                        $aviso->id
                    ),

                'leida' =>
                    false,

                'icono' =>
                    'megaphone',

                'color' =>
                    'blue',

                'created_at' =>
                    $ahora,

                'updated_at' =>
                    $ahora,
            ]);
        }
    }

    private function obtenerAfectados(
        Request $request,
        $empresaId
    ) {
        if ($request->aplica_a === 'todos') {
            return [
                'tipo' =>
                    'todos',

                'empresa_id' =>
                    $empresaId,
            ];
        }

        if ($request->aplica_a === 'oficina') {
            $oficinaIds =
                $request->input(
                    'afecta_a',
                    []
                );

            if (!is_array($oficinaIds)) {
                $oficinaIds = [
                    $oficinaIds
                ];
            }

            $oficinaIds =
                array_values(
                    array_unique(
                        array_filter(
                            array_map(
                                'intval',
                                $oficinaIds
                            )
                        )
                    )
                );

            if (empty($oficinaIds)) {
                abort(
                    back()
                        ->withInput()
                        ->withErrors([
                            'afecta_a' =>
                                'Debes seleccionar al menos una oficina.'
                        ])
                );
            }

            $cantidadValidos =
                Oficina::whereIn(
                    'id',
                    $oficinaIds
                )
                    ->where(
                        'empresa_id',
                        $empresaId
                    )
                    ->whereHas(
                        'departamento'
                    )
                    ->count();

            if (
                $cantidadValidos !==
                count($oficinaIds)
            ) {
                abort(
                    back()
                        ->withInput()
                        ->withErrors([
                            'afecta_a' =>
                                'Una o más oficinas no pertenecen a tu empresa o no tienen departamentos.'
                        ])
                );
            }

            return [
                'tipo' =>
                    'oficinas',

                'ids' =>
                    $oficinaIds,
            ];
        }

        if ($request->aplica_a === 'departamento') {
            $departamentoIds =
                $request->input(
                    'afecta_a',
                    []
                );

            if (!is_array($departamentoIds)) {
                $departamentoIds = [];
            }

            $departamentoIds =
                array_values(
                    array_unique(
                        array_map(
                            'intval',
                            $departamentoIds
                        )
                    )
                );

            if (empty($departamentoIds)) {
                abort(
                    back()
                        ->withInput()
                        ->withErrors([
                            'afecta_a' =>
                                'Debes seleccionar al menos un departamento.'
                        ])
                );
            }

            $cantidadValidos =
                Departamento::whereIn(
                    'id',
                    $departamentoIds
                )
                    ->whereHas(
                        'oficina',
                        function ($query) use ($empresaId) {
                            $query->where(
                                'empresa_id',
                                $empresaId
                            );
                        }
                    )
                    ->count();

            if (
                $cantidadValidos !==
                count($departamentoIds)
            ) {
                abort(
                    back()
                        ->withInput()
                        ->withErrors([
                            'afecta_a' =>
                                'Uno o más departamentos no pertenecen a tu empresa.'
                        ])
                );
            }

            return [
                'tipo' =>
                    'departamentos',

                'ids' =>
                    $departamentoIds,
            ];
        }

        if ($request->aplica_a === 'usuarios') {
            $logins =
                $request->input(
                    'afecta_a',
                    []
                );

            if (!is_array($logins)) {
                $logins = [];
            }

            $logins =
                array_values(
                    array_unique(
                        array_filter(
                            array_map(
                                'trim',
                                $logins
                            )
                        )
                    )
                );

            if (empty($logins)) {
                abort(
                    back()
                        ->withInput()
                        ->withErrors([
                            'afecta_a' =>
                                'Debes seleccionar al menos un usuario.'
                        ])
                );
            }

            $cantidadValidos =
                User::whereIn(
                    'login',
                    $logins
                )
                    ->whereHas(
                        'departamento.oficina',
                        function ($query) use ($empresaId) {
                            $query->where(
                                'empresa_id',
                                $empresaId
                            );
                        }
                    )
                    ->count();

            if (
                $cantidadValidos !==
                count($logins)
            ) {
                abort(
                    back()
                        ->withInput()
                        ->withErrors([
                            'afecta_a' =>
                                'Uno o más usuarios no pertenecen a tu empresa.'
                        ])
                );
            }

            return [
                'tipo' =>
                    'usuarios',

                'logins' =>
                    $logins,
            ];
        }

        return [];
    }

    private function obtenerUsuariosAfectados(
        array $afectaA,
        $empresaId
    ) {
        if (
            ($afectaA['tipo'] ?? null) ===
            'todos'
        ) {
            return User::with(
                'departamento.oficina'
            )
                ->whereHas(
                    'departamento.oficina',
                    function ($query) use ($empresaId) {
                        $query->where(
                            'empresa_id',
                            $empresaId
                        );
                    }
                )
                ->get();
        }

        if (
            ($afectaA['tipo'] ?? null) ===
            'oficinas'
        ) {
            return User::with(
                'departamento.oficina'
            )
                ->whereHas(
                    'departamento.oficina',
                    function ($query) use (
                        $afectaA,
                        $empresaId
                    ) {
                        $query
                            ->whereIn(
                                'id',
                                $afectaA['ids'] ?? []
                            )
                            ->where(
                                'empresa_id',
                                $empresaId
                            );
                    }
                )
                ->get();
        }

        if (
            ($afectaA['tipo'] ?? null) ===
            'oficina'
        ) {
            return User::with(
                'departamento.oficina'
            )
                ->whereHas(
                    'departamento.oficina',
                    function ($query) use (
                        $afectaA,
                        $empresaId
                    ) {
                        $query
                            ->where(
                                'id',
                                $afectaA['oficina_id']
                            )
                            ->where(
                                'empresa_id',
                                $empresaId
                            );
                    }
                )
                ->get();
        }

        if (
            ($afectaA['tipo'] ?? null) ===
            'departamentos'
        ) {
            return User::with(
                'departamento.oficina'
            )
                ->whereHas(
                    'departamento',
                    function ($query) use ($afectaA) {
                        $query->whereIn(
                            'id',
                            $afectaA['ids'] ?? []
                        );
                    }
                )
                ->whereHas(
                    'departamento.oficina',
                    function ($query) use ($empresaId) {
                        $query->where(
                            'empresa_id',
                            $empresaId
                        );
                    }
                )
                ->get();
        }

        if (
            ($afectaA['tipo'] ?? null) ===
            'usuarios'
        ) {
            return User::with(
                'departamento.oficina'
            )
                ->whereIn(
                    'login',
                    $afectaA['logins'] ?? []
                )
                ->whereHas(
                    'departamento.oficina',
                    function ($query) use ($empresaId) {
                        $query->where(
                            'empresa_id',
                            $empresaId
                        );
                    }
                )
                ->get();
        }

        return collect();
    }

    private function esTecnologias(
        User $usuario
    ): bool {
        $nombreDepartamento =
            $usuario
                ->departamento
                ?->nombre;

        if (!$nombreDepartamento) {
            return false;
        }

        $nombreDepartamento =
            mb_strtolower(
                trim($nombreDepartamento),
                'UTF-8'
            );

        $nombreDepartamento =
            str_replace(
                [
                    'á',
                    'é',
                    'í',
                    'ó',
                    'ú',
                    'ü'
                ],
                [
                    'a',
                    'e',
                    'i',
                    'o',
                    'u',
                    'u'
                ],
                $nombreDepartamento
            );

        return in_array(
            $nombreDepartamento,
            [
                'tecnologias',
                'tecnologia'
            ],
            true
        );
    }

    private function esRolExcluido(
        User $usuario
    ): bool {
        $rol =
            mb_strtolower(
                trim(
                    $usuario->role ?? ''
                ),
                'UTF-8'
            );

        $rol =
            str_replace(
                [
                    'á',
                    'é',
                    'í',
                    'ó',
                    'ú',
                    'ü'
                ],
                [
                    'a',
                    'e',
                    'i',
                    'o',
                    'u',
                    'u'
                ],
                $rol
            );

        return in_array(
            $rol,
            [
                'gerente ti',
                'soporte tecnico'
            ],
            true
        );
    }
}