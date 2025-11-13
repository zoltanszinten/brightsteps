<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ColorPaletteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']); // public

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'user']);
    Route::get('/color-palettes', [ColorPaletteController::class, 'index']);

    Route::get('/children', [UserController::class, 'children']);
    Route::post('/child', [UserController::class, 'childCreate']);
    Route::get('/child/{id}', [UserController::class, 'childDetail']);
    Route::put('/child/{id}', [UserController::class, 'childUpdate']);
    Route::delete('/child/{id}', [UserController::class, 'childDelete']);

    Route::get('/token/{id}', [AuthController::class, 'token']);

});
