<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        auth('admin')->user()->can('view permissions') ?: abort(403, 'Unauthorized');
        return view('dashboard.pages.permissions');
    }

    public function getAllPermissions(Request $request)
    {
        auth('admin')->user()->can('view permissions') ?: abort(403, 'Unauthorized');
        
        $permissions = Permission::where('guard_name', 'admin')
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->paginate($request->per_page ?? 10);

        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }

    public function store(Request $request)
    {
        auth('admin')->user()->can('create permissions') ?: abort(403, 'Unauthorized');
        
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'guard_name' => 'required|string|in:admin,web'
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permission created successfully',
            'data' => $permission
        ]);
    }

    public function update(Request $request, Permission $permission)
    {
        auth('admin')->user()->can('edit permissions') ?: abort(403, 'Unauthorized');
        
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'guard_name' => 'required|string|in:admin,web'
        ]);

        $permission->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permission updated successfully',
            'data' => $permission
        ]);
    }

    public function destroy(Permission $permission)
    {
        auth('admin')->user()->can('delete permissions') ?: abort(403, 'Unauthorized');
        
        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permission deleted successfully'
        ]);
    }
}