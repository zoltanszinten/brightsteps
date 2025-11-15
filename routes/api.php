<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ColorPaletteController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\FalseValueController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'user']);
    Route::get('/color-palettes', [ColorPaletteController::class, 'index']);
    Route::get('/color-palettes/{colorPalette}', [ColorPaletteController::class, 'details']);
    Route::get('/images', [ImageController::class, 'index']);

    Route::middleware(RoleMiddleware::class . ':admin')->group(function () {
        Route::get('/children', [UserController::class, 'children']);
        Route::post('/child', [UserController::class, 'childCreate']);
        Route::get('/child/{child}', [UserController::class, 'childDetail']);
        Route::put('/child/{child}', [UserController::class, 'childUpdate']);
        Route::delete('/child/{child}', [UserController::class, 'childDelete']);

        Route::get('/token/{id}', [AuthController::class, 'token']);

        Route::post('/images', [ImageController::class, 'store']);
        Route::delete('/images/{image}', [ImageController::class, 'destroy']);

        Route::get('/false-values', [FalseValueController::class, 'index']);
        Route::post('/false-values', [FalseValueController::class, 'store']);
        Route::delete('/false-values/{falseValue}', [FalseValueController::class, 'destroy']);
    });
});
