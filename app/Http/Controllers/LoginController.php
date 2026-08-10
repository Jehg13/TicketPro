<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
class LoginController extends Controller
{

    public function Login(Request $request){
    $datosValidados = $request->validate([
        "email" => 'required|email',
        'password' => 'required|string'
    ]);

    $remember = $request->boolean("remember");

    if(!Auth::attempt($datosValidados, $remember)){
        return redirect()->route('login')->with('error','El correo o la contraseña son incorrectos');
    }

    $request->session()->regenerate();

    $usuario = Auth::user();

    return match($usuario->rol){
    'usuario' => redirect()->route('dashboard'),
    'tecnologias' => redirect()->route('tecnologias'),
    default => abort(403,'Rol de usuario invalido')
    
    };
}
}