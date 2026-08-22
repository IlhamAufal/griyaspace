<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Organization;
use App\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('organization', 'role')->latest()->paginate(10);
        return view('pages.users.index', compact('users'));
    }

    public function create()
    {
        $organizations = Organization::where('is_active', true)->get();
        $roles = Role::where('is_active', true)->get();
        return view('pages.users.create', compact('organizations', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'organization_id' => 'nullable|exists:organizations,id',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean',
        ]);

        $tempPassword = Str::random(12);

        $validated['password'] = Hash::make($tempPassword);
        $validated['must_change_password'] = true;

        User::create($validated);

        return redirect()->route('users.index')->with('success', "User berhasil dibuat. Password sementara: {$tempPassword}");
    }

    public function show(User $user)
    {
        return view('pages.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $organizations = Organization::where('is_active', true)->get();
        $roles = Role::where('is_active', true)->get();
        return view('pages.users.edit', compact('user', 'organizations', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'organization_id' => 'nullable|exists:organizations,id',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean',
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function resetPassword(User $user)
    {
        $tempPassword = Str::random(12);

        $user->update([
            'password' => Hash::make($tempPassword),
            'must_change_password' => true,
        ]);

        return back()->with('success', "Password berhasil direset. Password sementara: {$tempPassword}");
    }

    public function destroy(User $user)
    {
        $user->update(['is_active' => false]);
        return redirect()->route('users.index')->with('success', 'User berhasil dinonaktifkan.');
    }
}
