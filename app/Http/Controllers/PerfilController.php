<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
}