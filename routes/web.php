<?php

use Illuminate\Support\Facades\Route;


Route::get('/connexion', function () {
    return view('auth.login');
})->name('login');

Route::get('/inscription', function () {
    return view('auth.signup');
})->name('login');  
Route::get('/index', function () {
    return view('auth.index');
})->name('login');  

Route::get('/hello', function () {
    return response()->json(['message' => 'Hello API']);
});