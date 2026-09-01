<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\AvisosusuarioController;
use App\Http\Controllers\Api\TicketApiController;
use App\Http\Controllers\Api\MisTicketsUsuarioController;
use App\Http\Controllers\Api\PerfilController;
use App\Http\Controllers\api\TecnologiasController;
use App\Http\Controllers\api\TicketsadminController;
use App\Http\Controllers\api\AvisosController;
use App\Http\Controllers\SolucionController;
use App\Http\Controllers\TicketComentarioController;
use App\Http\Controllers\Api\DispositivosController;
use App\Http\Controllers\Api\CambiosController;
use App\Http\Controllers\Api\ObtenerusuariosController;

Route::post('/login', [
    AuthController::class,
    'login'
]);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [
        AuthController::class,
        'user'
    ]);

    Route::post('/logout', [
        AuthController::class,
        'logout'
    ]);

    Route::get('/perfil', [
        PerfilController::class,
        'show'
    ]);

    Route::put('/perfil/password', [
        PerfilController::class,
        'actualizarPassword'
    ]);

    Route::put('/perfil/admin', [
        PerfilController::class,
        'actualizarPerfilAdmin'
    ]);

    Route::post('/perfil/foto', [
        PerfilController::class,
        'updateFoto'
    ]);

    Route::delete('/perfil/foto', [
        PerfilController::class,
        'deleteFoto'
    ]);

    Route::post('/perfil/solicitud-cambio', [
        PerfilController::class,
        'solicitarCambio'
    ]);

    Route::get('/tickets', [
        TicketController::class,
        'index'
    ])->name('api.tickets.index');

    Route::get('/tickets/{id}', [
        TicketController::class,
        'show'
    ])->name('api.tickets.show');

    Route::get('/avisos', [
        AvisosusuarioController::class,
        'index'
    ]);

    Route::get('/admin/avisos', [
        AvisosController::class,
        'index'
    ]);

    Route::post('/admin/avisos', [
        AvisosController::class,
        'store'
    ])->name('avisos.store');

    Route::get('/admin/avisos/{aviso}', [
        AvisosController::class,
        'show'
    ])->name('avisos.show');

    Route::put('/admin/avisos/{aviso}', [
        AvisosController::class,
        'update'
    ])->name('avisos.update');

    Route::delete('/admin/avisos/{aviso}', [
        AvisosController::class,
        'destroy'
    ])->name('avisos.destroy');

    Route::get('/equipos', [
        TicketApiController::class,
        'equipos'
    ]);

    Route::post('/ticketscrear', [
        TicketApiController::class,
        'store'
    ]);

    Route::get('/mis-tickets', [
        MisTicketsUsuarioController::class,
        'index'
    ]);

    Route::get('/mis-tickets/{id}', [
        MisTicketsUsuarioController::class,
        'show'
    ]);

    Route::post('/mis-tickets/{id}/comentarios', [
        MisTicketsUsuarioController::class,
        'agregarComentario'
    ]);

    Route::get('/mis-tickets-resumen', [
        MisTicketsUsuarioController::class,
        'resumen'
    ]);

    Route::get('/mis-tickets-notificaciones', [
        MisTicketsUsuarioController::class,
        'notificaciones'
    ]);

    Route::patch('/mis-tickets-notificaciones/{id}/leida', [
        MisTicketsUsuarioController::class,
        'marcarNotificacionLeida'
    ]);

    Route::patch('/mis-tickets-notificaciones-leer-todas', [
        MisTicketsUsuarioController::class,
        'marcarTodasNotificacionesLeidas'
    ]);

    Route::get('/tecnologias', [TecnologiasController::class, 'index']);
    Route::get('/tecnologias/evolucion', [TecnologiasController::class, 'evolucion']);

    Route::get('/admin/tickets', [
        TicketsadminController::class,
        'tecnologias'
    ]);

    Route::get('/admin/tickets/{id}', [
        TicketsadminController::class,
        'show'
    ]);

    Route::post('/admin/tickets/{id}/tomar', [
        TicketsadminController::class,
        'tomar'
    ]);

    Route::post('/admin/tickets/{ticket}/solucion', [
        SolucionController::class,
        'store'
    ]);

    Route::post('/admin/tickets/{ticket}/comentarios', [
        TicketComentarioController::class,
        'store'
    ]);
Route::get('/cambios', [CambiosController::class, 'index']);
    Route::get('/cambios/{id}', [CambiosController::class, 'show']);
    Route::patch('/cambios/{id}/aprobar', [CambiosController::class, 'aprobar']);
    Route::patch('/cambios/{id}/rechazar', [CambiosController::class, 'rechazar']);
    Route::get('/', [PerfilController::class, 'show']);
    Route::put('/', [PerfilController::class, 'update']);
    Route::put('/password', [PerfilController::class, 'actualizarPassword']);
    Route::post('/foto', [PerfilController::class, 'updateFoto']);
    Route::delete('/foto', [PerfilController::class, 'deleteFoto']);
    Route::get('/dispositivos', [DispositivosController::class, 'index']);
Route::post('/dispositivos', [DispositivosController::class, 'store']);
Route::get('/dispositivos/{id}', [DispositivosController::class, 'show']);
Route::put('/dispositivos/{id}', [DispositivosController::class, 'update']);
Route::patch('/dispositivos/{id}/estado', [DispositivosController::class, 'cambiarEstado']);
Route::delete('/dispositivos/{id}', [DispositivosController::class, 'destroy']);
Route::get('/usuarios', [ObtenerusuariosController::class, 'index']);
Route::get('/usuarios/empresas', [ObtenerusuariosController::class, 'empresas']);
Route::get('/usuarios/empresas/{empresaId}/oficinas', [ObtenerusuariosController::class, 'oficinasPorEmpresa']);
Route::get('/usuarios/oficinas/{oficinaId}/departamentos', [ObtenerusuariosController::class, 'departamentosPorOficina']);
Route::get('/usuarios/{login}', [ObtenerusuariosController::class, 'show']);
Route::put('/usuarios/{login}', [ObtenerusuariosController::class, 'update']);
Route::delete('/usuarios/{login}', [ObtenerusuariosController::class, 'destroy']);
});
