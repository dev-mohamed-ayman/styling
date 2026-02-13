<?php

namespace App\Livewire\Dashboard;

use App\Models\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class AdminRoles extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $per_page = 10;

    public $search = '';

    // Modal properties
    public $is_open = false;

    public $admin_id = null;

    public $admin_name = '';

    public $selected_roles = [];

    public function openModal($id)
    {
        $admin = Admin::findOrFail($id);
        $this->admin_id = $id;
        $this->admin_name = $admin->name;
        $this->selected_roles = $admin->roles->pluck('id')->toArray();
        $this->is_open = true;
    }

    public function closeModal()
    {
        $this->is_open = false;
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->admin_id = null;
        $this->admin_name = '';
        $this->selected_roles = [];
    }

    public function updateRoles()
    {
        $admin = Admin::findOrFail($this->admin_id);

        // Prevent removing all roles from self
        if ($admin->id === auth('admin')->id() && empty($this->selected_roles)) {
            $this->dispatch('toast', ['type' => 'error', 'message' => __('You cannot remove all roles from yourself')]);
            return;
        }

        // Prevent removing Super Admin role from the last Super Admin
        $currentRoles = $admin->roles->pluck('name')->toArray();
        $newRoles = Role::whereIn('id', $this->selected_roles)->pluck('name')->toArray();

        if (in_array('Super Admin', $currentRoles) && !in_array('Super Admin', $newRoles)) {
            $superAdminCount = Admin::role('Super Admin')->count();
            if ($superAdminCount <= 1) {
                $this->dispatch('toast', ['type' => 'error', 'message' => __('Cannot remove Super Admin role from the last Super Admin')]);
                return;
            }
        }

        $roles = Role::whereIn('id', $this->selected_roles)->get();
        $admin->syncRoles($roles);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Roles updated successfully')]);
    }

    public function render()
    {
        $admins = Admin::with('roles')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('username', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate($this->per_page);

        $roles = Role::where('guard_name', 'admin')->orderBy('name')->get();

        return view('livewire.dashboard.admin_roles', [
            'admins' => $admins,
            'roles' => $roles,
        ]);
    }
}
