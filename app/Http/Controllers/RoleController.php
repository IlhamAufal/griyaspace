<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $roles = $query->withCount('users')->latest()->paginate(10)->withQueryString();
        return view('pages.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('pages.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug ?: 'role';
        $counter = 1;
        while (Role::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }
        $validated['slug'] = $slug;

        Role::create($validated);

        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function show(Role $role)
    {
        $users = $role->users()->with('organization')->paginate(10);
        return view('pages.roles.show', compact('role', 'users'));
    }

    public function edit(Role $role)
    {
        return view('pages.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug ?: 'role';
        $counter = 1;
        while (Role::where('slug', $slug)->where('id', '!=', $role->id)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }
        $validated['slug'] = $slug;

        $role->update($validated);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Role tidak bisa dihapus karena masih digunakan oleh user.');
        }

        $role->update(['is_active' => false]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil dinonaktifkan.');
    }
}
