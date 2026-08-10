<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* METODOS POST */
Route::post('/login', [LoginController::class, 'Login'])->name('login.process');
Route::post('/logout', function (Request $request){

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');

})->name('logout');

/* RUTAS SIN HABER INICIADO SESION */

Route::get('/', function () {
    return view('welcome');
})->middleware('guest')->name('welcome');

Route::get('/login', function () {
    return view('login');
})->middleware('guest')->name('login');

Route::get('/conocer-mas', function () {
    return view('about');
})->middleware('guest')->name('about');

/* RUTAS DE LOS USUARIOS QUE CREARAN TICKETS */
Route::get('/dashboard', function () {
    return view('user.index');
})->middleware(['auth', 'role:usuario'])
  ->name('dashboard');

/* RUTAS DEL ADMINISTRADOR DE TI */
Route::get('/tecnologias', function () {
    return view('admin.index');
})
->middleware(['auth', 'role:tecnologias'])
->name('tecnologias');
