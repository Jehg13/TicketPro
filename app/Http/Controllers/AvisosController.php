<?php

namespace App\Http\Controllers;

use App\Models\Aviso;
use App\Models\User;
use App\Models\Departamento;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AvisosController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO DE AVISOS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $usuario = Auth::user();

        $empresaId = $usuario->departamento?->oficina?->empresa_id;

        if (!$empresaId) {

            return back()->withErrors([
                'empresa' =>
                    'No se pudo determinar la empresa del usuario actual.'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | DEPARTAMENTOS DE LA EMPRESA
        |--------------------------------------------------------------------------
        */

        $departamentos = Departamento::with('oficina')
            ->whereHas('oficina', function ($query) use ($empresaId) {

                $query->where(
                    'empresa_id',
                    $empresaId
                );
            })
            ->orderBy('nombre')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | USUARIOS DE LA EMPRESA
        |--------------------------------------------------------------------------
        */

        $usuarios = User::with('departamento.oficina')
            ->whereHas('departamento.oficina', function ($query) use ($empresaId) {

                $query->where(
                    'empresa_id',
                    $empresaId
                );
            })
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | AVISOS
        |--------------------------------------------------------------------------
        */

        $avisos = Aviso::with('publicadoPor')
            ->orderByDesc('fijado')
            ->orderByDesc('fecha_inicio')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES
        |--------------------------------------------------------------------------
        |
        | IMPORTANTE:
        |
        | En esta vista NO mostramos notificaciones de tipo "aviso".
        |
        | Esto evita que una notificación antigua de aviso que ya exista
        | en la base de datos siga apareciendo.
        |
        */

        $notificaciones = Notificacion::where(
            'user_id',
            $usuario->id
        )
            ->where(
                'tipo',
                '!=',
                'aviso'
            )
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES NO LEÍDAS
        |--------------------------------------------------------------------------
        |
        | Los avisos tampoco cuentan como notificaciones pendientes.
        |
        */

        $notificacionesNoLeidas = Notificacion::where(
            'user_id',
            $usuario->id
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


        /*
        |--------------------------------------------------------------------------
        | VISTA
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.avisos',
            compact(
                'avisos',
                'departamentos',
                'usuarios',
                'notificaciones',
                'notificacionesNoLeidas'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREAR AVISO
    |--------------------------------------------------------------------------
    */

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


        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'titulo' => [
                'required',
                'string',
                'max:255'
            ],

            'tipo' => [
                'required',
                'in:mantenimiento,incidente,informativo,general'
            ],

            'importancia' => [
                'required',
                'in:critica,alta,media,normal'
            ],

            'fecha_inicio' => [
                'required',
                'date'
            ],

            'hora_inicio' => [
                'required',
                'date_format:H:i'
            ],

            'fecha_fin' => [
                'nullable',
                'date',
                'after_or_equal:fecha_inicio'
            ],

            'hora_fin' => [
                'nullable',
                'date_format:H:i'
            ],

            'aplica_a' => [
                'required',
                'in:todos,departamento,usuarios'
            ],

            'descripcion' => [
                'required',
                'string',
                'max:1000'
            ],

            'mostrar_notificaciones' => [
                'nullable',
                'boolean'
            ],

            'fijado' => [
                'nullable',
                'boolean'
            ],

            'archivo' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf,mp4',
                'max:20480'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | FECHA DE INICIO
        |--------------------------------------------------------------------------
        */

        $validated['fecha_inicio'] =
            $request->fecha_inicio .
            ' ' .
            $request->hora_inicio .
            ':00';


        /*
        |--------------------------------------------------------------------------
        | FECHA DE FIN
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('fecha_fin') &&
            $request->filled('hora_fin')
        ) {

            $validated['fecha_fin'] =
                $request->fecha_fin .
                ' ' .
                $request->hora_fin .
                ':00';

        } else {

            $validated['fecha_fin'] = null;
        }


        /*
        |--------------------------------------------------------------------------
        | BOOLEANOS
        |--------------------------------------------------------------------------
        */

        $validated['mostrar_notificaciones'] =
            $request->boolean('mostrar_notificaciones');

        $validated['fijado'] =
            $request->boolean('fijado');


        /*
        |--------------------------------------------------------------------------
        | ARCHIVO
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('archivo')) {

            $validated['archivo'] =
                $request
                    ->file('archivo')
                    ->store(
                        'avisos',
                        'public'
                    );

        } else {

            $validated['archivo'] = null;
        }


        /*
        |--------------------------------------------------------------------------
        | USUARIOS AFECTADOS
        |--------------------------------------------------------------------------
        */

        $validated['afecta_a'] =
            $this->obtenerAfectados(
                $request,
                $empresaId
            );


        /*
        |--------------------------------------------------------------------------
        | USUARIO QUE PUBLICÓ
        |--------------------------------------------------------------------------
        */

        $validated['publicado_por'] = Auth::id();


        /*
        |--------------------------------------------------------------------------
        | CREAR AVISO
        |--------------------------------------------------------------------------
        */

        $aviso = Aviso::create($validated);


        /*
        |--------------------------------------------------------------------------
        | CREAR NOTIFICACIONES
        |--------------------------------------------------------------------------
        */

        if ($validated['mostrar_notificaciones']) {

            $usuariosAfectados =
                $this->obtenerUsuariosAfectados(
                    $validated['afecta_a'],
                    $empresaId
                );


            /*
            |--------------------------------------------------------------------------
            | NO NOTIFICAR AL USUARIO QUE PUBLICÓ
            |--------------------------------------------------------------------------
            */

            $usuariosAfectados =
                $usuariosAfectados->where(
                    'id',
                    '!=',
                    Auth::id()
                );


            /*
            |--------------------------------------------------------------------------
            | EXCLUIR TECNOLOGÍAS
            |--------------------------------------------------------------------------
            |
            | No usamos hasRole().
            |
            | Se identifica al usuario mediante su departamento.
            |
            */

            $usuariosAfectados =
                $usuariosAfectados->filter(
                    function ($usuarioAfectado) {

                        return !$this->esTecnologias(
                            $usuarioAfectado
                        );
                    }
                );


            /*
            |--------------------------------------------------------------------------
            | CREAR NOTIFICACIONES
            |--------------------------------------------------------------------------
            */

            $notificaciones = [];

            $ahora = now();


            foreach (
                $usuariosAfectados as $usuarioAfectado
            ) {

                $notificaciones[] = [

                    'user_id' =>
                        $usuarioAfectado->id,

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

                    'created_at' =>
                        $ahora,

                    'updated_at' =>
                        $ahora,
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | INSERTAR NOTIFICACIONES
            |--------------------------------------------------------------------------
            */

            if (!empty($notificaciones)) {

                Notificacion::insert(
                    $notificaciones
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | REDIRECCIÓN
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('avisostecnologias')
            ->with(
                'success',
                'Aviso publicado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR AVISO
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Aviso $aviso
    ) {

        $usuario = Auth::user();

        $empresaId =
            $usuario->departamento?->oficina?->empresa_id;


        if (!$empresaId) {

            return back()
                ->withInput()
                ->withErrors([
                    'aplica_a' =>
                        'No se pudo determinar la empresa del usuario actual.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'titulo' => [
                'required',
                'string',
                'max:255'
            ],

            'tipo' => [
                'required',
                'in:mantenimiento,incidente,informativo,general'
            ],

            'importancia' => [
                'required',
                'in:critica,alta,media,normal'
            ],

            'fecha_inicio' => [
                'required',
                'date'
            ],

            'hora_inicio' => [
                'required',
                'date_format:H:i'
            ],

            'fecha_fin' => [
                'nullable',
                'date',
                'after_or_equal:fecha_inicio'
            ],

            'hora_fin' => [
                'nullable',
                'date_format:H:i'
            ],

            'aplica_a' => [
                'required',
                'in:todos,departamento,usuarios'
            ],

            'descripcion' => [
                'required',
                'string',
                'max:1000'
            ],

            'mostrar_notificaciones' => [
                'nullable',
                'boolean'
            ],

            'fijado' => [
                'nullable',
                'boolean'
            ],

            'archivo' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf,mp4',
                'max:20480'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | FECHAS
        |--------------------------------------------------------------------------
        */

        $validated['fecha_inicio'] =
            $request->fecha_inicio .
            ' ' .
            $request->hora_inicio .
            ':00';


        if (
            $request->filled('fecha_fin') &&
            $request->filled('hora_fin')
        ) {

            $validated['fecha_fin'] =
                $request->fecha_fin .
                ' ' .
                $request->hora_fin .
                ':00';

        } else {

            $validated['fecha_fin'] = null;
        }


        /*
        |--------------------------------------------------------------------------
        | BOOLEANOS
        |--------------------------------------------------------------------------
        */

        $validated['mostrar_notificaciones'] =
            $request->boolean(
                'mostrar_notificaciones'
            );

        $validated['fijado'] =
            $request->boolean(
                'fijado'
            );


        /*
        |--------------------------------------------------------------------------
        | ARCHIVO
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('archivo')) {

            if ($aviso->archivo) {

                Storage::disk('public')
                    ->delete(
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

            unset(
                $validated['archivo']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | USUARIOS AFECTADOS
        |--------------------------------------------------------------------------
        */

        $validated['afecta_a'] =
            $this->obtenerAfectados(
                $request,
                $empresaId
            );


        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR AVISO
        |--------------------------------------------------------------------------
        */

        $aviso->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECCIÓN
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('avisostecnologias')
            ->with(
                'success',
                'Aviso actualizado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR AVISO
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Aviso $aviso
    ) {

        /*
        |--------------------------------------------------------------------------
        | ELIMINAR ARCHIVO
        |--------------------------------------------------------------------------
        */

        if ($aviso->archivo) {

            Storage::disk('public')
                ->delete(
                    $aviso->archivo
                );
        }


        /*
        |--------------------------------------------------------------------------
        | ELIMINAR AVISO
        |--------------------------------------------------------------------------
        */

        $aviso->delete();


        /*
        |--------------------------------------------------------------------------
        | REDIRECCIÓN
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('avisostecnologias')
            ->with(
                'success',
                'Aviso eliminado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER AFECTADOS
    |--------------------------------------------------------------------------
    */

    private function obtenerAfectados(
        Request $request,
        $empresaId
    ) {

        /*
        |--------------------------------------------------------------------------
        | TODOS
        |--------------------------------------------------------------------------
        */

        if ($request->aplica_a === 'todos') {

            return [

                'tipo' =>
                    'todos',

                'empresa_id' =>
                    $empresaId,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | DEPARTAMENTOS
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | USUARIOS
        |--------------------------------------------------------------------------
        */

        if ($request->aplica_a === 'usuarios') {

            $usuarioIds =
                $request->input(
                    'afecta_a',
                    []
                );


            if (!is_array($usuarioIds)) {

                $usuarioIds = [];
            }


            $usuarioIds =
                array_values(
                    array_unique(
                        array_map(
                            'intval',
                            $usuarioIds
                        )
                    )
                );


            if (empty($usuarioIds)) {

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
                    'id',
                    $usuarioIds
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
                count($usuarioIds)
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

                'ids' =>
                    $usuarioIds,
            ];
        }


        return [];
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER USUARIOS AFECTADOS
    |--------------------------------------------------------------------------
    */

    private function obtenerUsuariosAfectados(
        array $afectaA,
        $empresaId
    ) {

        /*
        |--------------------------------------------------------------------------
        | TODOS LOS USUARIOS DE LA EMPRESA
        |--------------------------------------------------------------------------
        */

        if (
            isset($afectaA['tipo']) &&
            $afectaA['tipo'] === 'todos'
        ) {

            return User::with(
                'departamento'
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


        /*
        |--------------------------------------------------------------------------
        | USUARIOS POR DEPARTAMENTO
        |--------------------------------------------------------------------------
        */

        if (
            isset($afectaA['tipo']) &&
            $afectaA['tipo'] === 'departamentos'
        ) {

            return User::with(
                'departamento'
            )
                ->whereHas(
                    'departamento',
                    function ($query) use ($afectaA) {

                        $query->whereIn(
                            'id',
                            $afectaA['ids']
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


        /*
        |--------------------------------------------------------------------------
        | USUARIOS ESPECÍFICOS
        |--------------------------------------------------------------------------
        */

        if (
            isset($afectaA['tipo']) &&
            $afectaA['tipo'] === 'usuarios'
        ) {

            return User::with(
                'departamento'
            )
                ->whereIn(
                    'id',
                    $afectaA['ids']
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


    /*
    |--------------------------------------------------------------------------
    | DETERMINAR SI UN USUARIO ES DE TECNOLOGÍAS
    |--------------------------------------------------------------------------
    */

    private function esTecnologias(
        User $usuario
    ): bool {

        $nombreDepartamento =
            $usuario->departamento?->nombre;


        if (!$nombreDepartamento) {

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZAR
        |--------------------------------------------------------------------------
        */

        $nombreDepartamento =
            mb_strtolower(
                trim($nombreDepartamento),
                'UTF-8'
            );


        /*
        |--------------------------------------------------------------------------
        | QUITAR ACENTOS
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | COMPROBAR TECNOLOGÍAS
        |--------------------------------------------------------------------------
        */

        return in_array(
            $nombreDepartamento,
            [
                'tecnologias',
                'tecnologia'
            ],
            true
        );
    }
}