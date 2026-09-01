<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Dispositivos;
use App\Models\User;
use App\Models\Notificacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DispositivosController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        $buscar = trim((string) $request->input('buscar', ''));
        $estado = strtolower(trim((string) $request->input('estado', '')));
        $porPagina = (int) $request->input('por_pagina', 10);

        if ($porPagina < 1) {
            $porPagina = 10;
        }

        if ($porPagina > 100) {
            $porPagina = 100;
        }

        try {
            $dispositivos = Dispositivos::with('usuario')
                ->when($buscar !== '', function ($query) use ($buscar) {
                    $query->where(function ($q) use ($buscar) {
                        $q->where('login', 'like', "%{$buscar}%")
                            ->orWhere('nombre_equipo', 'like', "%{$buscar}%")
                            ->orWhere('id_equipo', 'like', "%{$buscar}%")
                            ->orWhere('estado', 'like', "%{$buscar}%")
                            ->orWhereHas('usuario', function ($usuario) use ($buscar) {
                                $usuario->where('name', 'like', "%{$buscar}%");
                            });
                    });
                })
                ->when(
                    in_array($estado, ['vinculado', 'desvinculado'], true),
                    function ($query) use ($estado) {
                        $query->where('estado', $estado);
                    }
                )
                ->orderByDesc('id')
                ->paginate($porPagina)
                ->withQueryString();

            $usuarios = User::orderBy('name')
                ->get()
                ->map(function ($item) {
                    return [
                        'login' => $item->login,
                        'name' => $item->name,
                        'email' => $item->email,
                        'role' => $item->role,
                    ];
                })
                ->values();

            $notificaciones = Notificacion::where('login', $usuario->login)
                ->orderByDesc('created_at')
                ->limit(20)
                ->get();

            $notificacionesNoLeidas = Notificacion::where('login', $usuario->login)
                ->where('leida', false)
                ->count();

            $listaDispositivos = $dispositivos->getCollection()
                ->map(function ($dispositivo) {
                    return $this->formatearDispositivo($dispositivo);
                })
                ->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'dispositivos' => $listaDispositivos,
                    'usuarios' => $usuarios,
                    'notificaciones' => $notificaciones,
                    'notificaciones_no_leidas' => $notificacionesNoLeidas,
                    'pagination' => [
                        'current_page' => $dispositivos->currentPage(),
                        'last_page' => $dispositivos->lastPage(),
                        'per_page' => $dispositivos->perPage(),
                        'total' => $dispositivos->total(),
                        'from' => $dispositivos->firstItem(),
                        'to' => $dispositivos->lastItem(),
                    ],
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudieron obtener los dispositivos.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        try {
            $dispositivo = Dispositivos::with('usuario')->find($id);

            if (!$dispositivo) {
                return response()->json([
                    'success' => false,
                    'message' => 'El dispositivo no existe.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'dispositivo' => $this->formatearDispositivo($dispositivo),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener el dispositivo.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        try {
            $validated = $request->validate([
                'login' => [
                    'required',
                    'string',
                    'max:255',
                    'exists:users,login',
                ],
                'nombre_equipo' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'id_equipo' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:dispositivos,id_equipo',
                ],
                'estado' => [
                    'nullable',
                    'in:vinculado,desvinculado',
                ],
            ], [
                'login.required' => 'Debes seleccionar un usuario.',
                'login.exists' => 'El usuario seleccionado no existe.',
                'nombre_equipo.required' => 'Debes ingresar el nombre del equipo.',
                'nombre_equipo.max' => 'El nombre del equipo no puede superar los 255 caracteres.',
                'id_equipo.required' => 'Debes ingresar el ID del equipo.',
                'id_equipo.unique' => 'Este ID de equipo ya está registrado.',
                'id_equipo.max' => 'El ID del equipo no puede superar los 255 caracteres.',
                'estado.in' => 'El estado seleccionado no es válido.',
            ]);

            DB::beginTransaction();

            $dispositivo = Dispositivos::create([
                'login' => $validated['login'],
                'nombre_equipo' => $validated['nombre_equipo'],
                'id_equipo' => $validated['id_equipo'],
                'estado' => $validated['estado'] ?? 'vinculado',
            ]);

            DB::commit();

            $dispositivo->load('usuario');

            return response()->json([
                'success' => true,
                'message' => 'Dispositivo vinculado correctamente.',
                'data' => [
                    'dispositivo' => $this->formatearDispositivo($dispositivo),
                ],
            ], 201);
        } catch (ValidationException $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son válidos.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            return response()->json([
                'success' => false,
                'message' => 'No se pudo vincular el dispositivo.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        try {
            $dispositivo = Dispositivos::find($id);

            if (!$dispositivo) {
                return response()->json([
                    'success' => false,
                    'message' => 'El dispositivo no existe.',
                ], 404);
            }

            $validated = $request->validate([
                'login' => [
                    'required',
                    'string',
                    'max:255',
                    'exists:users,login',
                ],
                'nombre_equipo' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'id_equipo' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:dispositivos,id_equipo,' . $dispositivo->id,
                ],
                'estado' => [
                    'required',
                    'in:vinculado,desvinculado',
                ],
            ], [
                'login.required' => 'Debes seleccionar un usuario.',
                'login.exists' => 'El usuario seleccionado no existe.',
                'nombre_equipo.required' => 'Debes ingresar el nombre del equipo.',
                'nombre_equipo.max' => 'El nombre del equipo no puede superar los 255 caracteres.',
                'id_equipo.required' => 'Debes ingresar el ID del equipo.',
                'id_equipo.unique' => 'Este ID de equipo ya está registrado.',
                'id_equipo.max' => 'El ID del equipo no puede superar los 255 caracteres.',
                'estado.required' => 'Debes seleccionar un estado.',
                'estado.in' => 'El estado seleccionado no es válido.',
            ]);

            DB::beginTransaction();

            $dispositivo->update([
                'login' => $validated['login'],
                'nombre_equipo' => $validated['nombre_equipo'],
                'id_equipo' => $validated['id_equipo'],
                'estado' => $validated['estado'],
            ]);

            DB::commit();

            $dispositivo->refresh();
            $dispositivo->load('usuario');

            $mensaje = $validated['estado'] === 'vinculado'
                ? 'Dispositivo vinculado correctamente.'
                : 'Dispositivo desvinculado correctamente.';

            return response()->json([
                'success' => true,
                'message' => $mensaje,
                'data' => [
                    'dispositivo' => $this->formatearDispositivo($dispositivo),
                ],
            ]);
        } catch (ValidationException $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son válidos.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar el dispositivo.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

   public function cambiarEstado($id): JsonResponse
{
    $usuario = Auth::user();

    if (!$usuario) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no autenticado.',
        ], 401);
    }

    try {
        $dispositivo = Dispositivos::find($id);

        if (!$dispositivo) {
            return response()->json([
                'success' => false,
                'message' => 'El dispositivo no existe.',
            ], 404);
        }

        $estadoActual = trim((string) $dispositivo->estado);

        if (strcasecmp($estadoActual, 'Vinculado') === 0) {
            $nuevoEstado = 'Desvinculado';
        } else {
            $nuevoEstado = 'Vinculado';
        }

        $dispositivo->estado = $nuevoEstado;
        $dispositivo->save();
        $dispositivo->refresh();
        $dispositivo->load('usuario');

        $mensaje = $nuevoEstado === 'Vinculado'
            ? 'Dispositivo vinculado correctamente.'
            : 'Dispositivo desvinculado correctamente.';

        return response()->json([
            'success' => true,
            'message' => $mensaje,
            'data' => [
                'dispositivo' => $this->formatearDispositivo($dispositivo),
            ],
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'No se pudo cambiar el estado del dispositivo.',
            'error' => config('app.debug') ? $e->getMessage() : null,
        ], 500);
    }
}

    public function destroy($id): JsonResponse
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        try {
            $dispositivo = Dispositivos::find($id);

            if (!$dispositivo) {
                return response()->json([
                    'success' => false,
                    'message' => 'El dispositivo no existe.',
                ], 404);
            }

            $dispositivo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dispositivo eliminado correctamente.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar el dispositivo.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    private function formatearDispositivo(Dispositivos $dispositivo): array
    {
        return [
            'id' => $dispositivo->id,
            'login' => $dispositivo->login,
            'nombre_equipo' => $dispositivo->nombre_equipo,
            'id_equipo' => $dispositivo->id_equipo,
            'estado' => $dispositivo->estado,
            'usuario' => $dispositivo->usuario ? [
                'login' => $dispositivo->usuario->login,
                'name' => $dispositivo->usuario->name,
                'email' => $dispositivo->usuario->email,
                'role' => $dispositivo->usuario->role,
            ] : null,
            'created_at' => $dispositivo->created_at,
            'updated_at' => $dispositivo->updated_at,
        ];
    }
}
