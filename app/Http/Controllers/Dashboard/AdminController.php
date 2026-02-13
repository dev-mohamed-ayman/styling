<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::latest()->paginate(10);

        return view('dashboard.pages.admins', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.pages.admins');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins',
            'username' => 'required|string|max:255|unique:admins',
            'password' => 'required|min:6',
            'is_active' => 'boolean',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['is_active'] = $request->boolean('is_active', true);

        Admin::create($validatedData);

        return redirect()->route('dashboard.admins.index')
            ->with('success', 'Admin created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        return view('dashboard.pages.admins', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        return view('dashboard.pages.admins', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,'.$admin->id,
            'username' => 'required|string|max:255|unique:admins,username,'.$admin->id,
            'password' => 'nullable|min:6',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $validatedData['is_active'] = $request->boolean('is_active', true);

        $admin->update($validatedData);

        return redirect()->route('dashboard.admins.index')
            ->with('success', 'Admin updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        // Prevent deleting the last active admin
        $activeAdminsCount = Admin::where('is_active', true)->count();
        if ($activeAdminsCount <= 1 && $admin->is_active) {
            return redirect()->route('dashboard.admins.index')
                ->with('error', 'Cannot delete the last active admin.');
        }

        $admin->delete();

        return redirect()->route('dashboard.admins.index')
            ->with('success', 'Admin deleted successfully.');
    }
}
