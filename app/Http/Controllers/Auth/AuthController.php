<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('app.company.select');
        }

        return view('auth.login');
    }

    public function showPublicRegister()
    {
        if (Auth::check()) {
            return redirect()->route('app.company.select');
        }

        return view('auth.register-public');
    }

    public function publicRegister(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'company_name' => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $company = \App\Models\Company::create([
            'name' => $validated['company_name'],
            'slug' => \Illuminate\Support\Str::slug($validated['company_name']),
        ]);

        $company->users()->attach($user);

        Auth::login($user);

        return redirect()->route('app.dashboard', $company);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('app.company.select'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showRegister(User $user)
    {
        if (Auth::check()) {
            return redirect()->route('app.company.select');
        }

        if (!$user->invitation_code) {
            return redirect()->route('login')->with('error', 'Invalid or expired invitation link.');
        }

        $invitedUser = $user;
        return view('auth.register', compact('invitedUser'));
    }

    public function register(Request $request, User $user)
    {
        if (!$user->invitation_code) {
            return redirect()->route('login')->with('error', 'Invalid or expired invitation link.');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'password' => Hash::make($validated['password']),
            'invitation_code' => null,
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        return redirect()->route('app.company.select');
    }
}
