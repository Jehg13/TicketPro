<?php

namespace App\Http\Controllers;

use App\Models\Dispositivos;
use App\Models\User;
use Illuminate\Http\Request;

class DispositivosController extends Controller
{
    public function dispositivos()
    {
        $dispositivos = Dispositivos::with('usuario')
            ->orderBy('id', 'desc')
            ->get();

        $usuarios = User::orderBy('name')->get();

        return view('admin.dispositivos', compact(
            'dispositivos',
            'usuarios'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
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
            'login' => $request->login,
            'nombre_equipo' => $request->nombre_equipo,
            'id_equipo' => $request->id_equipo,
        ]);

        return redirect()
            ->route('dispositivos')
            ->with('success', 'Dispositivo vinculado correctamente.');
    }
}