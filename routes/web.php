<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ticketController;
use App\Http\Controllers\MisticketsController;
use App\Http\Controllers\AvisosController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\TicketComentarioController;
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
Route::post('/tickets/{ticket}/comentarios',[TicketComentarioController::class, 'store'])->name('tickets.comentarios.store');
Route::get('/tickets/{ticket}/comentarios', [TicketComentarioController::class, 'index'] )->name('tickets.comentarios.index');
Route::post('/logout', function (Request $request){
Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return redirect()->route('login'); })->name('logout');

/* RUTAS SIN HABER INICIADO SESION */
Route::middleware(['guest'])->group(function () {
Route::get('/olvidecontraseña', function(){ return view('forget-password'); })->name('olvidecontraseña');
Route::get('/conocer-mas', function () { return view('about'); })->name('about');  
Route::get('/login', function () { return view('login'); })->name('login');
Route::get('/', function () { return view('welcome'); })->name('welcome');
});


/* RUTAS DE LOS USUARIOS QUE CREARAN TICKETS */
Route::middleware(['auth', 'role:usuario'])->group(function () {
    Route::get('/dashboard', function () { return view('user.index'); })->name('dashboard');
    Route::get('/dashboard/tickets', [TicketController::class, 'create'])->name('ticketusuario');
    Route::post('/dashboard/tickets', [TicketController::class, 'store'])->name('ticketusuario.store');
    Route::get('/dashboard/mistickets', [MisticketsController::class, 'create'])->name('misticketusuario');
    Route::get('/dashboard/avisos', [AvisosController::class, 'create'])->name('avisosusuario');
    Route::get('/dashboard/perfil', [PerfilController::class, 'create'])->name('perfilusuario');
    Route::put('/dashboard/perfil/foto',[PerfilController::class, 'update'])->name('actualizarfoto');
    Route::delete('/dashboard/perfil/foto', [PerfilController::class, 'delete'])->name('eliminarfoto');
});


Route::middleware(['auth', 'role:tecnologias'])->group(function(){
    Route::get('/tecnologias', function () { return view('admin.index'); })->name('tecnologias'); 
    Route::get('/tecnologias/tickets', [MisticketsController::class, 'tecnologias'])->name('tickettecnologias');
    Route::get('/tecnologias/avisos', function(){ return view('admin.avisos'); })->name('avisostecnologias');
    Route::get('/tecnologias/cambios', function(){ return view('admin.cambios'); })->name('cambiostecnologias');
    Route::get('/tecnologias/perfil', function(){ return view('admin.perfil'); })->name('perfiltecnologias');
});



