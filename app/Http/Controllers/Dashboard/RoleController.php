<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        auth('admin')->user()->can('view roles') ?: abort(403, 'Unauthorized');
        return view('dashboard.pages.roles');
    }

    public function adminRoles()
    {
        auth('admin')->user()->can('assign roles') ?: abort(403, 'Unauthorized');
        return view('dashboard.pages.admin_roles');
    }

    public function getAllRoles(Request $request)
    {
        auth('admin')->user()->can('view roles') ?: abort(403, 'Unauthorized');
        
        $roles = Role::where('guard_name', 'admin')
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->paginate($request->per_page ?? 10);

        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    }

    public function store(Request $request)
    {
        auth('admin')->user()->can('create roles') ?: abort(403, 'Unauthorized');
        
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'required|string|in:admin,web'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully',
            'data' => $role
        ]);
    }

    public function update(Request $request, Role $role)
    {
        auth('admin')->user()->can('edit roles') ?: abort(403, 'Unauthorized');
        
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'guard_name' => 'required|string|in:admin,web'
        ]);

        $role->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully',
            'data' => $role
        ]);
    }

    public function destroy(Role $role)
    {
        auth('admin')->user()->can('delete roles') ?: abort(403, 'Unauthorized');
        
        // Prevent deletion of system roles
        if (in_array($role->name, ['Super Admin', 'Admin', 'Moderator'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete system roles'
            ], 400);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ]);
    }

    public function getPermissionsByRole(Role $role)
    {
        auth('admin')->user()->can('view permissions') ?: abort(403, 'Unauthorized');
        
        $permissions = Permission::where('guard_name', 'admin')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return response()->json([
            'success' => true,
            'data' => [
                'role' => $role,
                'permissions' => $permissions,
                'role_permissions' => $rolePermissions
            ]
        ]);
    }

    public function updateRolePermissions(Request $request, Role $role)
    {
        auth('admin')->user()->can('assign permissions') ?: abort(403, 'Unauthorized');
        
        $request->validate([
            'permissions' => 'array'
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return response()->json([
            'success' => true,
            'message' => 'Role permissions updated successfully'
        ]);
    }
}