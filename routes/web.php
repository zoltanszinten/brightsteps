<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('app'); // or inertia(...)
})->where('any', '^(?!api/|sanctum/|telescope/).*$');
