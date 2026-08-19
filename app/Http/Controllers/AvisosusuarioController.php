<?php

namespace App\Http\Controllers;

use App\Models\Aviso;
use App\Models\Departamento;
use App\Models\User;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Auth;

class AvisosusuarioController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO DE AVISOS DEL USUARIO
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $usuario = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | DATOS DEL USUARIO
        |--------------------------------------------------------------------------
        */

        $empresaId =
            $usuario->departamento?->oficina?->empresa_id;

        $departamentoId =
            $usuario->departamento?->id;

        $usuarioId =
            $usuario->id;


        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES DEL USUARIO
        |--------------------------------------------------------------------------
        |
        | IMPORTANTE:
        |
        | Aquí obtenemos las notificaciones del usuario actual.
        |
        | No creamos notificaciones.
        |
        | Las notificaciones de tipo "aviso" para Tecnologías
        | son excluidas desde AvisosController.
        |
        */

        $notificaciones =
            Notificacion::where(
                'user_id',
                $usuarioId
            )
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES NO LEÍDAS
        |--------------------------------------------------------------------------
        */

        $notificacionesNoLeidas =
            Notificacion::where(
                'user_id',
                $usuarioId
            )
            ->where(
                'leida',
                false
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | SI EL USUARIO NO TIENE EMPRESA
        |--------------------------------------------------------------------------
        */

        if (!$empresaId) {

            return view('user.avisos', [

                'avisos' =>
                    collect(),

                'usuarios' =>
                    collect(),

                'departamentos' =>
                    collect(),

                'notificaciones' =>
                    $notificaciones,

                'notificacionesNoLeidas' =>
                    $notificacionesNoLeidas,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | OBTENER AVISOS ACTIVOS
        |--------------------------------------------------------------------------
        */

        $avisos =
            Aviso::with('publicadoPor')
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
                ->get()
                ->filter(function ($aviso) use (
                    $empresaId,
                    $departamentoId,
                    $usuarioId
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | OBTENER AFECTADOS
                    |--------------------------------------------------------------------------
                    */

                    $afecta =
                        $aviso->afecta_a;


                    /*
                    |--------------------------------------------------------------------------
                    | CONVERTIR JSON A ARRAY
                    |--------------------------------------------------------------------------
                    */

                    if (
                        is_string($afecta)
                    ) {

                        $afecta =
                            json_decode(
                                $afecta,
                                true
                            );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | VALIDAR ESTRUCTURA
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !is_array($afecta)
                    ) {

                        return false;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | AVISO PARA TODA LA EMPRESA
                    |--------------------------------------------------------------------------
                    */

                    if (
                        ($afecta['tipo'] ?? null)
                        === 'todos'
                    ) {

                        if (
                            isset(
                                $afecta['empresa_id']
                            ) &&
                            (int)
                            $afecta['empresa_id']
                            !==
                            (int)
                            $empresaId
                        ) {

                            return false;
                        }

                        return true;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | AVISO PARA DEPARTAMENTOS
                    |--------------------------------------------------------------------------
                    */

                    if (
                        ($afecta['tipo'] ?? null)
                        === 'departamentos'
                    ) {

                        $departamentoIds =
                            $afecta['ids'] ?? [];


                        if (
                            !is_array(
                                $departamentoIds
                            )
                        ) {

                            return false;
                        }


                        $departamentoIds =
                            array_map(
                                'intval',
                                $departamentoIds
                            );


                        return in_array(
                            (int)
                            $departamentoId,
                            $departamentoIds,
                            true
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | AVISO PARA USUARIOS ESPECÍFICOS
                    |--------------------------------------------------------------------------
                    */

                    if (
                        ($afecta['tipo'] ?? null)
                        === 'usuarios'
                    ) {

                        $usuarioIds =
                            $afecta['ids'] ?? [];


                        if (
                            !is_array(
                                $usuarioIds
                            )
                        ) {

                            return false;
                        }


                        $usuarioIds =
                            array_map(
                                'intval',
                                $usuarioIds
                            );


                        return in_array(
                            (int)
                            $usuarioId,
                            $usuarioIds,
                            true
                        );
                    }


                    return false;
                })
                ->sortByDesc('fijado')
                ->sortByDesc('fecha_inicio')
                ->values();


        /*
        |--------------------------------------------------------------------------
        | PREPARAR INFORMACIÓN DE LOS AVISOS
        |--------------------------------------------------------------------------
        */

        foreach (
            $avisos
            as $aviso
        ) {

            $afecta =
                $aviso->afecta_a;


            /*
            |--------------------------------------------------------------------------
            | CONVERTIR JSON A ARRAY
            |--------------------------------------------------------------------------
            */

            if (
                is_string($afecta)
            ) {

                $afecta =
                    json_decode(
                        $afecta,
                        true
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | VALOR POR DEFECTO
            |--------------------------------------------------------------------------
            */

            if (
                !is_array($afecta)
            ) {

                $afecta = [];
            }


            /*
            |--------------------------------------------------------------------------
            | GUARDAR AFECTA_A COMO ARRAY
            |--------------------------------------------------------------------------
            */

            $aviso->afecta_a =
                $afecta;


            /*
            |--------------------------------------------------------------------------
            | VALORES POR DEFECTO
            |--------------------------------------------------------------------------
            */

            $aviso->afecta_texto =
                'Todos los usuarios';

            $aviso->afecta_usuarios =
                [];

            $aviso->afecta_departamentos =
                [];


            /*
            |--------------------------------------------------------------------------
            | TODA LA EMPRESA
            |--------------------------------------------------------------------------
            */

            if (
                ($afecta['tipo'] ?? null)
                === 'todos'
            ) {

                $aviso->afecta_texto =
                    'Toda la empresa';

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | DEPARTAMENTOS
            |--------------------------------------------------------------------------
            */

            if (
                ($afecta['tipo'] ?? null)
                === 'departamentos'
            ) {

                $ids =
                    $afecta['ids'] ?? [];


                if (
                    !is_array($ids) ||
                    empty($ids)
                ) {

                    $aviso->afecta_texto =
                        'Departamentos';

                    continue;
                }


                $ids =
                    array_map(
                        'intval',
                        $ids
                    );


                /*
                |--------------------------------------------------------------------------
                | OBTENER DEPARTAMENTOS
                |--------------------------------------------------------------------------
                */

                $departamentosAviso =
                    Departamento::with('oficina')
                        ->whereIn(
                            'id',
                            $ids
                        )
                        ->orderBy('nombre')
                        ->get();


                /*
                |--------------------------------------------------------------------------
                | INFORMACIÓN PARA ALPINE
                |--------------------------------------------------------------------------
                */

                $aviso->afecta_departamentos =
                    $departamentosAviso
                        ->map(function (
                            $departamento
                        ) {

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
                        })
                        ->values()
                        ->toArray();


                /*
                |--------------------------------------------------------------------------
                | TEXTO DEL AVISO
                |--------------------------------------------------------------------------
                */

                $nombres =
                    $departamentosAviso
                        ->map(function (
                            $departamento
                        ) {

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
                        })
                        ->implode(', ');


                $aviso->afecta_texto =
                    $nombres
                    ?: 'Departamentos';


                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | USUARIOS ESPECÍFICOS
            |--------------------------------------------------------------------------
            */

            if (
                ($afecta['tipo'] ?? null)
                === 'usuarios'
            ) {

                $ids =
                    $afecta['ids'] ?? [];


                if (
                    !is_array($ids) ||
                    empty($ids)
                ) {

                    $aviso->afecta_texto =
                        'Usuarios específicos';

                    continue;
                }


                $ids =
                    array_map(
                        'intval',
                        $ids
                    );


                /*
                |--------------------------------------------------------------------------
                | OBTENER USUARIOS
                |--------------------------------------------------------------------------
                */

                $usuariosAviso =
                    User::with(
                        'departamento.oficina'
                    )
                    ->whereIn(
                        'id',
                        $ids
                    )
                    ->orderBy('name')
                    ->get();


                /*
                |--------------------------------------------------------------------------
                | INFORMACIÓN PARA ALPINE
                |--------------------------------------------------------------------------
                */

                $aviso->afecta_usuarios =
                    $usuariosAviso
                        ->map(function (
                            $usuario
                        ) {

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
                        })
                        ->values()
                        ->toArray();


                /*
                |--------------------------------------------------------------------------
                | TEXTO DEL AVISO
                |--------------------------------------------------------------------------
                */

                $nombres =
                    $usuariosAviso
                        ->map(function (
                            $usuario
                        ) {

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
                        })
                        ->implode(', ');


                $aviso->afecta_texto =
                    $nombres
                    ?: 'Usuarios específicos';
            }
        }


        /*
        |--------------------------------------------------------------------------
        | USUARIOS DISPONIBLES PARA LA VISTA
        |--------------------------------------------------------------------------
        */

        $usuarios =
            User::with(
                'departamento'
            )
            ->orderBy('name')
            ->get()
            ->map(function (
                $usuario
            ) {

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
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | DEPARTAMENTOS DISPONIBLES PARA LA VISTA
        |--------------------------------------------------------------------------
        */

        $departamentos =
            Departamento::orderBy(
                'nombre'
            )
            ->get()
            ->map(function (
                $departamento
            ) {

                return [

                    'id' =>
                        $departamento->id,

                    'nombre' =>
                        $departamento->nombre,
                ];
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | VISTA
        |--------------------------------------------------------------------------
        */

        return view(
            'user.avisos',
            [

                'avisos' =>
                    $avisos,

                'usuarios' =>
                    $usuarios,

                'departamentos' =>
                    $departamentos,

                'notificaciones' =>
                    $notificaciones,

                'notificacionesNoLeidas' =>
                    $notificacionesNoLeidas,
            ]
        );
    }
}