<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\AvisosusuarioController;
use App\Http\Controllers\Api\TicketApiController;
use App\Http\Controllers\Api\MisTicketsUsuarioController;


/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

Route::post('/login', [
    AuthController::class,
    'login'
]);


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | USUARIO
    |--------------------------------------------------------------------------
    */

    Route::get('/user', [
        AuthController::class,
        'user'
    ]);

    Route::post('/logout', [
        AuthController::class,
        'logout'
    ]);


    /*
    |--------------------------------------------------------------------------
    | TICKETS GENERALES
    |--------------------------------------------------------------------------
    */

    Route::get('/tickets', [
        TicketController::class,
        'index'
    ])->name('api.tickets.index');

    Route::get('/tickets/{id}', [
        TicketController::class,
        'show'
    ])->name('api.tickets.show');


    /*
    |--------------------------------------------------------------------------
    | AVISOS
    |--------------------------------------------------------------------------
    */

    Route::get('/avisos', [
        AvisosusuarioController::class,
        'index'
    ]);


    /*
    |--------------------------------------------------------------------------
    | EQUIPOS
    |--------------------------------------------------------------------------
    */

    Route::get('/equipos', [
        TicketApiController::class,
        'equipos',
    ]);


    /*
    |--------------------------------------------------------------------------
    | CREAR TICKET
    |--------------------------------------------------------------------------
    */

    Route::post('/ticketscrear', [
        TicketApiController::class,
        'store',
    ]);


    /*
    |--------------------------------------------------------------------------
    | MIS TICKETS - USUARIO
    |--------------------------------------------------------------------------
    */

    Route::get('/mis-tickets', [
        MisTicketsUsuarioController::class,
        'index'
    ]);


    /*
    |--------------------------------------------------------------------------
    | DETALLE DE MI TICKET
    |--------------------------------------------------------------------------
    */

    Route::get('/mis-tickets/{id}', [
        MisTicketsUsuarioController::class,
        'show'
    ]);


    /*
    |--------------------------------------------------------------------------
    | RESUMEN DE MIS TICKETS
    |--------------------------------------------------------------------------
    */

    Route::get('/mis-tickets-resumen', [
        MisTicketsUsuarioController::class,
        'resumen'
    ]);


    /*
    |--------------------------------------------------------------------------
    | NOTIFICACIONES
    |--------------------------------------------------------------------------
    */

    Route::get('/mis-tickets-notificaciones', [
        MisTicketsUsuarioController::class,
        'notificaciones'
    ]);


    /*
    |--------------------------------------------------------------------------
    | MARCAR UNA NOTIFICACIÓN COMO LEÍDA
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/mis-tickets-notificaciones/{id}/leida',
        [
            MisTicketsUsuarioController::class,
            'marcarNotificacionLeida'
        ]
    );


    /*
    |--------------------------------------------------------------------------
    | MARCAR TODAS LAS NOTIFICACIONES COMO LEÍDAS
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/mis-tickets-notificaciones-leer-todas',
        [
            MisTicketsUsuarioController::class,
            'marcarTodasNotificacionesLeidas'
        ]
    );
});
