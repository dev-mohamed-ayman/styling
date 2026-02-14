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

    public function openPermissionModal($roleId)
    {
        $this->role_id = $roleId;
        $role = Role::find($roleId);
        $this->selected_permissions = $role->permissions->pluck('id')->toArray();
        $this->is_permission_modal_open = true;
    }

    public function closePermissionModal()
    {
        $this->is_permission_modal_open = false;
        $this->role_id = null;
        $this->selected_permissions = [];
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
        $this->role_id = $id;
        $this->name = $role->name;
        $this->guard_name = $role->guard_name;
        $this->is_open = true;
    }

    public function store()
    {
        $this->validate();

        Role::create([
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Role created successfully')]);
    }

    public function update()
    {
        $this->validate();

        $role = Role::findOrFail($this->role_id);
        $role->update([
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Role updated successfully')]);
    }

    public function updatePermissions()
    {
        $role = Role::findOrFail($this->role_id);
        $role->syncPermissions($this->selected_permissions);

        $this->closePermissionModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Role permissions updated successfully')]);
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);

        // Prevent deletion of system roles
        if (in_array($role->name, ['Super Admin', 'Admin', 'Moderator'])) {
            $this->dispatch('toast', ['type' => 'error', 'message' => __('Cannot delete system roles')]);
            return;
        }

        $role->delete();

        $this->dispatch('toast', ['type' => 'success', 'message' => __('Role deleted successfully')]);
    }

    public function render()
    {
        $roles = Role::query()
            ->where('guard_name', 'admin')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate($this->per_page);

        return view('livewire.dashboard.roles', [
            'roles' => $roles,
        ]);
    }
}