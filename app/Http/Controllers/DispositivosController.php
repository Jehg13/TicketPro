<?php

namespace App\Http\Controllers;

use App\Models\Dispositivos;
use App\Models\User;
use Illuminate\Http\Request;

class DispositivosController extends Controller
{
    public function dispositivos(Request $request)
    {
        $buscar = trim($request->input('buscar', ''));
        $estado = $request->input('estado', '');

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
                in_array($estado, ['vinculado', 'desvinculado']),
                function ($query) use ($estado) {
                    $query->where('estado', $estado);
                }
            )
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $usuarios = User::orderBy('name')->get();

        return view('admin.dispositivos', compact(
            'dispositivos',
            'usuarios',
            'buscar',
            'estado'
        ));
    }

    public function store(Request $request)
    {
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
        ], [
            'login.required' => 'Debes seleccionar un usuario.',
            'login.exists' => 'El usuario seleccionado no existe.',
            'nombre_equipo.required' => 'Debes ingresar el nombre del equipo.',
            'id_equipo.required' => 'Debes ingresar el ID del equipo.',
            'id_equipo.unique' => 'Este ID de equipo ya está registrado.',
        ]);

        Dispositivos::create([
            'login' => $validated['login'],
            'nombre_equipo' => $validated['nombre_equipo'],
            'id_equipo' => $validated['id_equipo'],
            'estado' => 'vinculado',
        ]);

        return $this->redirectWithFilters(
            $request,
            'Dispositivo vinculado correctamente.'
        );
    }

    public function update(Request $request, $id)
    {
        $dispositivo = Dispositivos::findOrFail($id);

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
            'id_equipo.required' => 'Debes ingresar el ID del equipo.',
            'id_equipo.unique' => 'Este ID de equipo ya está registrado.',
            'estado.required' => 'Debes seleccionar un estado.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ]);

        $dispositivo->update([
            'login' => $validated['login'],
            'nombre_equipo' => $validated['nombre_equipo'],
            'id_equipo' => $validated['id_equipo'],
            'estado' => $validated['estado'],
        ]);

        $mensaje = $validated['estado'] === 'vinculado'
            ? 'Dispositivo vinculado correctamente.'
            : 'Dispositivo desvinculado correctamente.';

        return $this->redirectWithFilters($request, $mensaje);
    }

    public function cambiarEstado(Request $request, $id)
    {
        $dispositivo = Dispositivos::findOrFail($id);

        $nuevoEstado = $dispositivo->estado === 'vinculado'
            ? 'desvinculado'
            : 'vinculado';

        $dispositivo->update([
            'estado' => $nuevoEstado,
        ]);

        $mensaje = $nuevoEstado === 'vinculado'
            ? 'Dispositivo vinculado correctamente.'
            : 'Dispositivo desvinculado correctamente.';

        return $this->redirectWithFilters($request, $mensaje);
    }

    public function destroy(Request $request, $id)
    {
        $dispositivo = Dispositivos::findOrFail($id);

        $dispositivo->delete();

        return $this->redirectWithFilters(
            $request,
            'Dispositivo eliminado correctamente.'
        );
    }

    private function redirectWithFilters(Request $request, string $mensaje)
    {
        $params = array_filter([
            'buscar' => trim($request->input('buscar', '')),
            'estado' => $request->input('estado', ''),
            'page' => $request->input('page'),
        ], function ($value) {
            return $value !== null && $value !== '';
        });

        return redirect()
            ->route('dispositivos', $params)
            ->with('success', $mensaje);
    }
}
