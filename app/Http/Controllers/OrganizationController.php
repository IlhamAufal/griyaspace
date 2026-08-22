<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::latest()->paginate(10);
        return view('pages.organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('pages.organizations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:255|unique:organizations,abbreviation',
            'leader_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        Organization::create($validated);

        return redirect()->route('organizations.index')->with('success', 'Organisasi berhasil ditambahkan.');
    }

    public function show(Organization $organization)
    {
        return view('pages.organizations.show', compact('organization'));
    }

    public function edit(Organization $organization)
    {
        return view('pages.organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:255|unique:organizations,abbreviation,' . $organization->id,
            'leader_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $organization->update($validated);

        return redirect()->route('organizations.index')->with('success', 'Organisasi berhasil diperbarui.');
    }

    public function destroy(Organization $organization)
    {
        $organization->update(['is_active' => false]);
        return redirect()->route('organizations.index')->with('success', 'Organisasi berhasil dinonaktifkan.');
    }
}
