<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\AvisosusuarioController;
use App\Http\Controllers\Api\TicketApiController;
use App\Http\Controllers\Api\MisTicketsUsuarioController;
use App\Http\Controllers\Api\PerfilController;

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
});
