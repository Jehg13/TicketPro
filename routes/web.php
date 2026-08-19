<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ticketController;
use App\Http\Controllers\MisticketsController;
use App\Http\Controllers\AvisosController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\TicketComentarioController;
use App\Http\Controllers\AvisosusuarioController;
use App\Http\Controllers\SolicitudCambioController;
use App\Http\Controllers\SolucionController;
use App\Http\Controllers\TecnologiasController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\NotificacionController;
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

Route::post('/login', [LoginController::class, 'Login'])
    ->name('login.process');

Route::post('/tickets/{ticket}/comentarios', [TicketComentarioController::class, 'store'])
    ->name('tickets.comentarios.store');

Route::get('/tickets/{ticket}/comentarios', [TicketComentarioController::class, 'index'])
    ->name('tickets.comentarios.index');

Route::post('/tickets/{ticket}/tomar', [MisTicketsController::class, 'tomar'])
    ->name('tickets.tomar');

Route::post('/logout', function (Request $request) {

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');

})->name('logout');


/* RUTAS SIN HABER INICIADO SESION */

Route::middleware(['guest'])->group(function () {

    Route::get('/olvidecontraseña', function () {
        return view('forget-password');
    })->name('olvidecontraseña');


    Route::post('/olvidecontraseña', function (Request $request) {

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {

            return back()->with(
                'status',
                'Te hemos enviado un enlace para restablecer tu contraseña.'
            );

        }

        return back()->withErrors([
            'email' => 'No encontramos un usuario registrado con ese correo electrónico.',
        ]);

    })->name('password.email');


    Route::get('/reset-password/{token}', function (
        string $token,
        Request $request
    ) {

        return view('reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);

    })->name('password.reset');


    Route::post('/reset-password', function (Request $request) {

        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[0-9]/', 'regex:/[^A-Za-z0-9]/',],
        ]);

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function ($user, $password) {

                $user->password = Hash::make($password);
                $user->password_updated_at = now();
                $user->save();

            }
        );

        if ($status === Password::PASSWORD_RESET) {

            return redirect()
                ->route('login')
                ->with(
                    'status',
                    'Tu contraseña ha sido restablecida correctamente.'
                );

        }

        return back()->withErrors([
            'email' => 'El enlace para restablecer la contraseña no es válido o ha expirado.',
        ]);

    })->name('password.update');


    Route::get('/conocer-mas', function () {
        return view('about');
    })->name('about');


    Route::get('/login', function () {
        return view('login');
    })->name('login');


    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

});


/* RUTAS DE LOS USUARIOS QUE CREARAN TICKETS */

Route::middleware(['auth', 'role:usuario'])->group(function () {

   Route::get('/dashboard', [ UsuarioController::class,'index'])
   ->name('dashboard');

       Route::patch('/dashboard/notificaciones/marcar-leidas', [NotificacionController::class, 'marcarLeidas'])
    ->name('notificacionesusuario.marcarLeidas');

    Route::get('/dashboard/tickets', [TicketController::class, 'create'])
        ->name('ticketusuario');

    Route::post('/dashboard/tickets', [TicketController::class, 'store'])
        ->name('ticketusuario.store');

    Route::get('/dashboard/tickets/{ticket}', [UsuarioController::class, 'verTicket'])
    ->name('ticketusuario.detalles');
    
        Route::get('/dashboard/mistickets', [MisticketsController::class, 'create'])
        ->name('misticketusuario');

    Route::get('/dashboard/avisos', [AvisosusuarioController::class, 'create'])
        ->name('avisosusuario');

    Route::get('/dashboard/perfil', [PerfilController::class, 'create'])
        ->name('perfilusuario');

    Route::put('/dashboard/perfil/foto', [PerfilController::class, 'update'])
        ->name('actualizarfoto');

    Route::delete('/dashboard/perfil/foto', [PerfilController::class, 'delete'])
        ->name('eliminarfoto');

    Route::post('/dashboard/solicitar-cambio', [PerfilController::class, 'solicitarCambio'])
    ->name('solicitar.cambio.store');

});


/* RUTAS DE TECNOLOGIAS */

Route::middleware(['auth', 'role:tecnologias'])->group(function () {

 Route::get('/tecnologias', [TecnologiasController::class, 'index'])
    ->name('tecnologias');
    Route::patch('/notificaciones/marcar-leidas', [NotificacionController::class, 'marcarLeidas'])
    ->name('notificaciones.marcarLeidas');
Route::get('/tecnologias/evolucion', [TecnologiasController::class, 'evolucion'])
    ->name('tecnologias.evolucion');

    Route::get('/tecnologias/tickets', [MisticketsController::class, 'tecnologias'])
        ->name('tickettecnologias');
    Route::post(
        '/tecnologias/tickets/{ticket}/solucion', [SolucionController::class, 'store'])
        ->name('tickets.solucion.store');

    Route::get('/tecnologias/avisos', [AvisosController::class, 'index'])
        ->name('avisostecnologias');

    Route::put('/tecnologias/avisos/{aviso}', [AvisosController::class, 'update'])
        ->name('avisos.update');

    Route::post('/tecnologias/avisos', [AvisosController::class, 'store'])
        ->name('avisos.store');

    Route::delete('/tecnologias/avisos/{aviso}', [AvisosController::class, 'destroy'])
        ->name('avisos.destroy');

    Route::get('/tecnologias/avisos/{aviso}', [AvisosController::class, 'show'])
    ->name('avisos.show');

    Route::get(
        '/tecnologias/cambios',
        [SolicitudCambioController::class, 'index']
    )->name('cambiostecnologias');

    Route::post(
        '/tecnologias/cambios/{solicitud}/aprobar',
        [SolicitudCambioController::class, 'aprobar']
    )->name('cambios.aprobar');

    Route::post(
        '/tecnologias/cambios/{solicitud}/rechazar',
        [SolicitudCambioController::class, 'rechazar']
    )->name('cambios.rechazar');

    Route::get('/tecnologias/perfil', [PerfilController::class, 'create'])
        ->name('perfiltecnologias');

    Route::put('/tecnologias/perfil/foto', [PerfilController::class, 'update'])
        ->name('perfil.update');

    Route::delete('/tecnologias/perfil/foto', [PerfilController::class, 'delete'])
        ->name('perfil.delete');

    Route::put('/tecnologias/perfil', [PerfilController::class, 'updateTecnologias'])
        ->name('tecnologias.perfil.update');

});