<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\SolicitudCambio;
use App\Models\User;

class PerfilController extends Controller
{
    public function create()
    {
        $user = auth()->user();

        if ($user->rol === 'tecnologias') {
            return view('admin.perfil');
        }

        return view('user.perfil');
    }


    public function update(Request $request)
    {
        $request->validate([
            'foto' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ]);
/** @var \App\Models\User $user */
        $user = auth()->user();

        if (
            $user->foto &&
            $user->foto !== 'profile-photos/user.png'
        ) {
            Storage::disk('public')->delete($user->foto);
        }

        $path = $request->file('foto')
            ->store('profile-photos', 'public');

        $user->foto = $path;
        $user->save();


        return back()->with(
            'success',
            'Foto de perfil actualizada correctamente.'
        );
    }


    public function delete()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (
            $user->foto &&
            $user->foto !== 'profile-photos/user.png'
        ) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->foto = 'profile-photos/user.png';

        $user->save();


        return back()->with(
            'success',
            'Foto de perfil eliminada correctamente.'
        );
    }


    public function updateTecnologias(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->rol !== 'tecnologias') {
            abort(403);
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],

            'departamento' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($user->departamento) {
            $user->departamento->nombre = $request->departamento;
            $user->departamento->save();
        }

        $user->save();

        return back()->with(
            'success',
            'Información personal actualizada correctamente.'
        );
    }

public function solicitarCambio(Request $request)
{
    $request->validate([
        'campo' => 'required|string|in:nombre,correo,oficina,departamento,ubicacion',
        'nuevo_valor' => 'required|string|max:255',
        'motivo' => 'required|string|max:1000',
    ]);

    /** @var \App\Models\User $user */
    $user = Auth::user();

    switch ($request->campo) {

        case 'nombre':
            $valorActual = $user->name;
            break;

        case 'correo':
            $valorActual = $user->email;
            break;

        case 'departamento':
            $valorActual = $user->departamento
                ? $user->departamento->nombre
                : null;
            break;

        case 'oficina':
            $valorActual = $user->departamento && $user->departamento->oficina
                ? $user->departamento->oficina->nombre
                : null;
            break;

        case 'ubicacion':
            $valorActual = $user->ubicacion;
            break;

        default:
            $valorActual = null;
            break;
    }

    SolicitudCambio::create([
        'user_id' => $user->id,
        'campo' => $request->campo,
        'valor_actual' => $valorActual,
        'nuevo_valor' => $request->nuevo_valor,
        'motivo' => $request->motivo,
        'estado' => 'pendiente',
    ]);

    return back()->with(
        'success',
        'Tu solicitud de cambio fue enviada correctamente.'
    );
}
}