<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

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
use App\Http\Controllers\MfaController;
use App\Http\Controllers\DispositivosController;
use App\Http\Controllers\BackupsController;
use App\Http\Controllers\ObtenerusuariosController;


Route::post(
    '/login',
    [LoginController::class, 'Login']
)->name('login.process');


Route::post('/logout', function (Request $request) {

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');

})->name('logout');


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


    Route::get(
        '/reset-password/{token}',
        function (string $token, Request $request) {

            return view('reset-password', [
                'token' => $token,
                'email' => $request->email,
            ]);

        }
    )->name('password.reset');


    Route::post('/reset-password', function (Request $request) {

        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
            ],
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
            'email' => 'El enlace para restablecer tu contraseña no es válido o ha expirado.',
        ]);

    })->name('password.update');


    Route::get(
        '/conocer-mas',
        function () {
            return view('about');
        }
    )->name('about');


    Route::get(
        '/login',
        function () {
            return view('login');
        }
    )->name('login');


    Route::get(
        '/',
        function () {
            return view('welcome');
        }
    )->name('welcome');

        Route::post(
        '/login/mfa/verificar',
        [MfaController::class, 'verificar']
    )->name('mfa.verificar');

});


Route::middleware(['auth'])->group(function () {

    Route::post(
        '/tickets/{ticket}/comentarios',
        [TicketComentarioController::class, 'store']
    )->name('tickets.comentarios.store');


    Route::get(
        '/tickets/{ticket}/comentarios',
        [TicketComentarioController::class, 'index']
    )->name('tickets.comentarios.index');


    Route::get(
        '/dashboard/perfil/mfa/configurar',
        [MfaController::class, 'configurar']
    )->name('usuario.mfa.configurar');


    Route::post(
        '/dashboard/perfil/mfa/verificar-activacion',
        [MfaController::class, 'verificarActivacion']
    )->name('usuario.mfa.verificar.activacion');


    Route::get(
        '/perfil/mfa/configurar',
        [MfaController::class, 'configurar']
    )->name('mfa.configurar');


    Route::post(
        '/perfil/mfa/verificar-activacion',
        [MfaController::class, 'verificarActivacion']
    )->name('mfa.verificar.activacion');


    Route::post(
        '/perfil/mfa/desactivar',
        [MfaController::class, 'desactivar']
    )->name('mfa.desactivar');


    Route::patch(
        '/notificaciones/marcar-leidas',
        [NotificacionController::class, 'marcarLeidas']
    )->name('notificaciones.marcarLeidas');

});


Route::middleware(['auth', 'role:no-admin'])->group(function () {

    Route::get(
        '/dashboard',
        [UsuarioController::class, 'index']
    )->name('dashboard');


    Route::get(
        '/dashboard/tickets',
        [ticketController::class, 'create']
    )->name('ticketusuario');


    Route::post(
        '/dashboard/tickets',
        [ticketController::class, 'store']
    )->name('ticketusuario.store');


    Route::get(
        '/dashboard/tickets/{ticket}',
        [UsuarioController::class, 'verTicket']
    )->name('ticketusuario.detalles');


    Route::get(
        '/dashboard/mistickets',
        [MisticketsController::class, 'create']
    )->name('misticketusuario');


    Route::get(
        '/dashboard/avisos',
        [AvisosusuarioController::class, 'create']
    )->name('avisosusuario');


    Route::get(
        '/dashboard/perfil',
        [PerfilController::class, 'create']
    )->name('perfilusuario');


    Route::put(
        '/dashboard/perfil/foto',
        [PerfilController::class, 'update']
    )->name('actualizarfoto');


    Route::put(
        '/dashboard/perfil/password',
        [PerfilController::class, 'actualizarPassword']
    )->name('perfil.password.update');


    Route::delete(
        '/dashboard/perfil/foto',
        [PerfilController::class, 'delete']
    )->name('eliminarfoto');


    Route::post(
        '/dashboard/solicitar-cambio',
        [PerfilController::class, 'solicitarCambio']
    )->name('solicitar.cambio.store');

});


Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get(
        '/tecnologias',
        [TecnologiasController::class, 'index']
    )->name('tecnologias');


    Route::get(
        '/tecnologias/evolucion',
        [TecnologiasController::class, 'evolucion']
    )->name('tecnologias.evolucion');


    Route::get(
        '/tecnologias/tickets',
        [MisticketsController::class, 'tecnologias']
    )->name('tickettecnologias');


    Route::post(
        '/tickets/{ticket}/tomar',
        [MisticketsController::class, 'tomar']
    )->name('tickets.tomar');


    Route::post(
        '/tecnologias/tickets/{ticket}/solucion',
        [SolucionController::class, 'store']
    )->name('tickets.solucion.store');


    Route::get(
        '/tecnologias/avisos',
        [AvisosController::class, 'index']
    )->name('avisostecnologias');


    Route::post(
        '/tecnologias/avisos',
        [AvisosController::class, 'store']
    )->name('avisos.store');


    Route::get(
        '/tecnologias/avisos/{aviso}',
        [AvisosController::class, 'show']
    )->name('avisos.show');


    Route::put(
        '/tecnologias/avisos/{aviso}',
        [AvisosController::class, 'update']
    )->name('avisos.update');


    Route::delete(
        '/tecnologias/avisos/{aviso}',
        [AvisosController::class, 'destroy']
    )->name('avisos.destroy');


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


    Route::get(
        '/tecnologias/perfil',
        [PerfilController::class, 'create']
    )->name('perfiltecnologias');


    Route::put(
        '/tecnologias/perfil/foto',
        [PerfilController::class, 'update']
    )->name('perfil.update');


    Route::delete(
        '/tecnologias/perfil/foto',
        [PerfilController::class, 'delete']
    )->name('perfil.delete');


    Route::put(
        '/tecnologias/perfil',
        [PerfilController::class, 'updateTecnologias']
    )->name('tecnologias.perfil.update');


    Route::put(
        '/tecnologias/perfil/password',
        [PerfilController::class, 'actualizarPassword']
    )->name('tecnologias.perfil.password.update');


    Route::get(
        '/tecnologias/usuarios',
        [ObtenerusuariosController::class, 'index']
    )->name('usuarios.tecnologias');


    Route::get(
        '/tecnologias/usuarios/{login}',
        [ObtenerusuariosController::class, 'show']
    )->name('usuarios.show');


    Route::delete(
        '/tecnologias/usuarios/{login}',
        [ObtenerusuariosController::class, 'destroy']
    )->name('usuarios.eliminar');


    Route::put(
        '/tecnologias/usuarios/{login}',
        [ObtenerusuariosController::class, 'update']
    )->name('usuarios.update');


    Route::get(
        '/tecnologias/empresas',
        [ObtenerusuariosController::class, 'empresas']
    )->name('tecnologias.empresas');


    Route::get(
        '/tecnologias/oficinas/{empresaId}',
        [ObtenerusuariosController::class, 'oficinasPorEmpresa']
    )->name('tecnologias.oficinas');


    Route::get(
        '/tecnologias/departamentos/{oficinaId}',
        [ObtenerusuariosController::class, 'departamentosPorOficina']
    )->name('tecnologias.departamentos');

    Route::get('/tecnologias/dispositivos/',
    [DispositivosController::class, 'dispositivos'])->name('dispositivos');

    Route::post('/tecnologias/dispositivos',[DispositivosController::class, 'store'])->name('dispositivos.store');
    Route::delete('/tecnologias/dispositivos',[DispositivosController::class, 'destroy'])->name('dispositivos.destroy');
    Route::put('/dispositivos/{id}', [DispositivosController::class, 'update'])
    ->name('dispositivos.update');

Route::get('/tecnologias/backups', [BackupsController::class, 'index'])
    ->name('backups');

Route::post('/tecnologias/backups/configurar', [BackupsController::class, 'guardarConfiguracion'])
    ->name('backups.configurar');

Route::post('/tecnologias/backups/activar', [BackupsController::class, 'activar'])
    ->name('backups.activar');

Route::post('/tecnologias/backups/desactivar', [BackupsController::class, 'desactivar'])
    ->name('backups.desactivar');

Route::post('/tecnologias/backups/crear', [BackupsController::class, 'crearManual'])
    ->name('backups.crear');

Route::post('/tecnologias/backups/manual', [BackupsController::class, 'crearManual'])
    ->name('backups.manual');

Route::get('/tecnologias/backups/{id}/descargar', [BackupsController::class, 'descargar'])
    ->name('backups.descargar');

Route::delete('/tecnologias/backups/{id}', [BackupsController::class, 'destroy'])
    ->name('backups.eliminar');
    });

Route::get('/archivo-aviso/{path}', function ($path) {

    $path = ltrim($path, '/');

    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    $contenido = Storage::disk('public')->get($path);
    $mime = Storage::disk('public')->mimeType($path);

    return response($contenido, 200)
        ->header('Content-Type', $mime ?: 'application/octet-stream')
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
        ->header('Access-Control-Allow-Headers', '*')
        ->header('Cache-Control', 'public, max-age=3600');
})->where('path', '.*');