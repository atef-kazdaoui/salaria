<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function index()
    {
        return view('auth.twofactor');
    }

    public function store(Request $request)
    {
        $request->validate(['two_factor_code' => 'required']);

        $user = User::find(session('2fa:user:id'));

        if (!$user || $user->two_factor_code !== $request->two_factor_code) {
            return back()->with('error', 'Code invalide.');
        }

        if ($user->two_factor_expires_at->lt(now())) {
            return back()->with('error', 'Le code a expiré.');
        }

        $user->resetTwoFactorCode();

        Auth::login($user);

        return redirect()->route('index')->with('success', 'Connexion réussie.');
    }
}
