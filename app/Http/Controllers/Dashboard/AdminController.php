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
        $currentUser = auth('admin')->user();
        
        // Only super admins can create admins with specific roles
        if (!$currentUser->hasRole('Super Admin') && ($request->has('role') || $request->has('is_super_admin'))) {
            return redirect()->route('dashboard.admins.index')
                ->with('error', 'Only Super Admins can assign roles.');
        }
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins',
            'username' => 'required|string|max:255|unique:admins',
            'password' => 'required|min:6',
            'is_active' => 'boolean',
            'role' => 'sometimes|in:admin,moderator',
            'is_super_admin' => 'boolean',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['is_active'] = $request->boolean('is_active', true);
        
        // Set default role if not specified
        if (!$request->has('role')) {
            $validatedData['role'] = 'admin';
        }
        
        // Only super admins can set role and is_super_admin
        if ($currentUser->hasRole('Super Admin')) {
            if ($request->has('role')) {
                $validatedData['role'] = $request->input('role');
            }
            if ($request->has('is_super_admin')) {
                $validatedData['is_super_admin'] = $request->boolean('is_super_admin');
            }
        }

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
        $currentUser = auth('admin')->user();
        
        // Prevent non-super admins from modifying super admins
        if (!$currentUser->hasRole('Super Admin') && $admin->hasRole('Super Admin')) {
            return redirect()->route('dashboard.admins.index')
                ->with('error', 'Only Super Admins can modify Super Admin accounts.');
        }
        
        // Prevent non-super admins from changing roles
        if (!$currentUser->hasRole('Super Admin') && $request->has('role')) {
            return redirect()->route('dashboard.admins.index')
                ->with('error', 'Only Super Admins can assign roles.');
        }
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,'.$admin->id,
            'username' => 'required|string|max:255|unique:admins,username,'.$admin->id,
            'password' => 'nullable|min:6',
            'is_active' => 'boolean',
            'role' => 'sometimes|in:admin,moderator',
            'is_super_admin' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $validatedData['is_active'] = $request->boolean('is_active', true);
        
        // Only super admins can update role and is_super_admin fields
        if ($currentUser->hasRole('Super Admin')) {
            if ($request->has('role')) {
                $validatedData['role'] = $request->input('role');
            }
            if ($request->has('is_super_admin')) {
                $validatedData['is_super_admin'] = $request->boolean('is_super_admin');
            }
            
            // Prevent non-super admin from demoting themselves
            if ($admin->id == $currentUser->id && !$request->boolean('is_super_admin', false)) {
                return redirect()->route('dashboard.admins.index')
                    ->with('error', 'You cannot remove Super Admin status from yourself.');
            }
        }

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
