<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function create()
    {
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
}