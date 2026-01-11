<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Company $company)
    {
        $users = $company->users()->paginate(20);

        return view('app.users.index', compact('company', 'users'));
    }

    public function create(Company $company)
    {
        return view('app.users.create', compact('company'));
    }

    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $company->users()->attach($user);

        return redirect()->route('app.users.index', $company)
            ->with('success', 'User created and added to company.');
    }

    public function show(Company $company, User $user)
    {
        return view('app.users.show', compact('company', 'user'));
    }

    public function edit(Company $company, User $user)
    {
        return view('app.users.edit', compact('company', 'user'));
    }

    public function update(Request $request, Company $company, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('app.users.show', [$company, $user])
            ->with('success', 'User updated.');
    }

    public function destroy(Company $company, User $user)
    {
        $company->users()->detach($user);

        return redirect()->route('app.users.index', $company)
            ->with('success', 'User removed from company.');
    }
}
