<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class Permissions extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $per_page = 10;

    public $search = '';

    // Modal properties
    public $is_open = false;

    public $permission_id = null;

    public $name = '';

    public $guard_name = 'admin';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:permissions,name,'.$this->permission_id,
            'guard_name' => 'required|string|in:admin,web',
        ];
    }

    protected $messages = [
        'name.required' => 'The permission name is required.',
        'name.unique' => 'This permission name already exists.',
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

    private function resetInput()
    {
        $this->permission_id = null;
        $this->name = '';
        $this->guard_name = 'admin';
    }

    public function create()
    {
        $this->openModal();
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $this->permission_id = $id;
        $this->name = $permission->name;
        $this->guard_name = $permission->guard_name;
        $this->is_open = true;
    }

    public function store()
    {
        $this->validate();

        Permission::create([
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Permission created successfully')]);
    }

    public function update()
    {
        $this->validate();

        $permission = Permission::findOrFail($this->permission_id);
        $permission->update([
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Permission updated successfully')]);
    }

    public function delete($id)
    {
        $permission = Permission::findOrFail($id);

        $permission->delete();

        $this->dispatch('toast', ['type' => 'success', 'message' => __('Permission deleted successfully')]);
    }

    public function render()
    {
        $permissions = Permission::query()
            ->where('guard_name', 'admin')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate($this->per_page);

        return view('livewire.dashboard.permissions', [
            'permissions' => $permissions,
        ]);
    }
}