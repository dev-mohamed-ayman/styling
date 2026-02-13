<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Roles extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $per_page = 10;

    public $search = '';

    // Modal properties
    public $is_open = false;

    public $is_permission_modal_open = false;

    public $role_id = null;

    public $name = '';

    public $guard_name = 'admin';

    public $selected_permissions = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name,'.$this->role_id,
            'guard_name' => 'required|string|in:admin,web',
            'selected_permissions' => 'nullable|array',
        ];
    }

    protected $messages = [
        'name.required' => 'The role name is required.',
        'name.unique' => 'This role name already exists.',
        'guard_name.required' => 'The guard name is required.',
    ];

    public function openModal()
    {
        $this->resetInput();
        $this->is_open = true;
    }

    public function closeModal()
    {
        $this->is_open = false;
        $this->resetInput();
    }

    public function openPermissionModal($id)
    {
        $role = Role::findOrFail($id);
        $this->role_id = $id;
        $this->name = $role->name;
        $this->selected_permissions = $role->permissions->pluck('id')->toArray();
        $this->is_permission_modal_open = true;
    }

    public function closePermissionModal()
    {
        $this->is_permission_modal_open = false;
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->role_id = null;
        $this->name = '';
        $this->guard_name = 'admin';
        $this->selected_permissions = [];
    }

    public function create()
    {
        $this->openModal();
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        // Prevent editing system roles
        if (in_array($role->name, ['Super Admin'])) {
            $this->dispatch('toast', ['type' => 'error', 'message' => __('Cannot edit system roles')]);
            return;
        }

        $this->role_id = $id;
        $this->name = $role->name;
        $this->guard_name = $role->guard_name;
        $this->is_open = true;
    }

    public function store()
    {
        $this->validate();

        $role = Role::create([
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Create Role: :name', ['name' => $role->name])]);
    }

    public function update()
    {
        $this->validate();

        $role = Role::findOrFail($this->role_id);

        // Prevent editing system roles
        if (in_array($role->name, ['Super Admin'])) {
            $this->dispatch('toast', ['type' => 'error', 'message' => __('Cannot edit system roles')]);
            return;
        }

        $role->update([
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Edit Role: :name', ['name' => $role->name])]);
    }

    public function updatePermissions()
    {
        $role = Role::findOrFail($this->role_id);

        // Prevent modifying system roles permissions directly
        if (in_array($role->name, ['Super Admin'])) {
            $this->dispatch('toast', ['type' => 'error', 'message' => __('Cannot modify Super Admin permissions')]);
            return;
        }

        $permissions = Permission::whereIn('id', $this->selected_permissions)->get();
        $role->syncPermissions($permissions);

        $this->closePermissionModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Update Role Permissions: :name', ['name' => $role->name])]);
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);

        // Prevent deleting system roles
        if (in_array($role->name, ['Super Admin', 'Admin', 'User'])) {
            $this->dispatch('toast', ['type' => 'error', 'message' => __('Cannot delete system roles')]);
            return;
        }

        $name = $role->name;
        $role->delete();

        $this->dispatch('toast', ['type' => 'success', 'message' => __('Delete Role: :name', ['name' => $name])]);
    }

    public function render()
    {
        $roles = Role::with('permissions')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy('name')
            ->paginate($this->per_page);

        $permissions = Permission::where('guard_name', 'admin')
            ->orderBy('name')
            ->get()
            ->groupBy(function ($permission) {
                $parts = explode(' ', $permission->name);
                return end($parts);
            });

        return view('livewire.dashboard.roles', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }
}
