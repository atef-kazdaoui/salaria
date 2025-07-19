<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Auth\TwoFactorController;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// --- Public Routes ---
Route::get('/connexion', function () {
    return view('auth.login');
})->name('login');

Route::get('/inscription', function () {
    return view('auth.signup');
})->name('signup');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/connexion')->with('success', 'Vous avez été déconnecté.');
})->name('logout');

// --- Protected Route (authenticated and verified users only) ---
Route::get('/index', function () {
    return view('auth.index');
})->middleware(['auth', 'verified'])->name('index');

// --- Email Verification Link Handling ---
Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $user = User::findOrFail($id);

    // Validate the hash from the email
    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Invalid verification link.');
    }

    // Mark email as verified if not already
    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    return redirect('/connexion')->with('success', 'Your email has been verified. You can now log in.');
})->middleware('signed')->name('verification.verify');

// --- Resend verification email manually ---
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification email resent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// --- Two-Factor Authentication (2FA) routes ---
Route::get('/2fa', [TwoFactorController::class, 'index'])->name('2fa.index');
Route::post('/2fa', [TwoFactorController::class, 'store'])->name('2fa.store');

// --- API-style auth endpoints for registration and login ---
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
