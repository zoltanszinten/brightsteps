<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ColorPaletteController;
use App\Http\Controllers\GameImageController;
use App\Http\Controllers\RecognitionFalseValueController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']); // public

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'user']);
    Route::get('/color-palettes', [ColorPaletteController::class, 'index']);

    Route::middleware('role:admin')->group(function () {
        Route::get('/children', [UserController::class, 'children']);
        Route::post('/child', [UserController::class, 'childCreate']);
        Route::get('/child/{child}', [UserController::class, 'childDetail']);
        Route::put('/child/{child}', [UserController::class, 'childUpdate']);
        Route::delete('/child/{child}', [UserController::class, 'childDelete']);

        Route::get('/token/{id}', [AuthController::class, 'token']);

        Route::get('/game-images', [GameImageController::class, 'index']);
        Route::post('/game-images', [GameImageController::class, 'store']);
        Route::delete('/game-images/{gameImage}', [GameImageController::class, 'destroy']);

        Route::get('/recognition-false-values', [RecognitionFalseValueController::class, 'index']);
        Route::post('/recognition-false-values', [RecognitionFalseValueController::class, 'store']);
        Route::delete('/recognition-false-values/{recognitionFalseValue}', [RecognitionFalseValueController::class, 'destroy']);
    });
});
