<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


// Login Flutter
Route::post('/login', [
    AuthController::class,
    'login'
]);


// Rutas protegidas por Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Usuario autenticado
    Route::get('/user', [
        AuthController::class,
        'user'
    ]);

    // Logout
    Route::post('/logout', [
        AuthController::class,
        'logout'
    ]);

});