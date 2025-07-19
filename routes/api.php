<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;

Route::post('/register', [AuthController::class, 'register'])->name('api.register');

