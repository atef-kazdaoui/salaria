<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\TwoFactorCode;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        // 1. Validate input fields
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // 2. Create the user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Send email verification
        event(new Registered($user));

        // 4. Redirect to login with success message
        return redirect('/connexion')->with('success', 'Compte créé avec succès. Veuillez vérifier votre adresse e-mail pour la confirmer.');
    }

    /**
     * Login and trigger 2FA via email
     */
    public function login(Request $request)
    {
        // 1. Validate login fields
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');

        // 2. Attempt to authenticate
        if (!Auth::attempt($credentials)) {
            return redirect()->back()->with('error', 'Adresse e-mail ou mot de passe invalide.');
        }

        $user = Auth::user();

        // 3. Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            return redirect()->back()->with('error', 'Please verify your email address before logging in.');
        }

        // 4. Generate and send 2FA code
        $user->generateTwoFactorCode();
        $user->notify(new TwoFactorCode());

        // 5. Temporarily log out and store user ID in session
        Auth::logout();
        session(['2fa:user:id' => $user->id]);

        return redirect()->route('2fa.index');
    }
}
