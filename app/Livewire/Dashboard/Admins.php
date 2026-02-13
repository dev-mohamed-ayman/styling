<?php

namespace App\Livewire\Dashboard;

use App\Models\Admin as AdminModel;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class Admins extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $per_page = 10;

    public $search = '';

    // Modal properties
    public $is_open = false;

    public $admin_id = null;

    public $name = '';

    public $email = '';

    public $username = '';

    public $password = '';

    public $is_active = true;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,'.$this->admin_id,
            'username' => 'required|string|max:255|unique:admins,username,'.$this->admin_id,
            'password' => $this->admin_id ? 'nullable|min:6' : 'required|min:6',
            'is_active' => 'boolean',
        ];

        return $rules;
    }

    protected $messages = [
        'name.required' => 'The name field is required.',
        'email.required' => 'The email field is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already taken.',
        'username.required' => 'The username field is required.',
        'username.unique' => 'This username is already taken.',
        'password.required' => 'The password field is required.',
        'password.min' => 'The password must be at least 6 characters.',
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
        $this->admin_id = null;
        $this->name = '';
        $this->email = '';
        $this->username = '';
        $this->password = '';
        $this->is_active = true;
    }

    public function create()
    {
        $this->openModal();
    }

    public function edit($id)
    {
        $admin = AdminModel::findOrFail($id);
        $this->admin_id = $id;
        $this->name = $admin->name;
        $this->email = $admin->email;
        $this->username = $admin->username;
        $this->is_active = $admin->is_active;
        $this->password = '';
        $this->is_open = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $admin = AdminModel::create($data);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Create Admin: :name', ['name' => $admin->name])]);
    }

    public function update()
    {
        $this->validate();

        $admin = AdminModel::findOrFail($this->admin_id);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $admin->update($data);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Edit Admin: :name', ['name' => $admin->name])]);
    }

    public function delete($id)
    {
        $admin = AdminModel::findOrFail($id);

        // Prevent deleting the last active admin
        $activeAdminsCount = AdminModel::where('is_active', true)->count();
        if ($activeAdminsCount <= 1 && $admin->is_active) {
            $this->dispatch('toast', ['type' => 'error', 'message' => __('Cannot delete the last active admin')]);
            return;
        }

        $name = $admin->name;
        $admin->delete();

        $this->dispatch('toast', ['type' => 'success', 'message' => __('Delete Admin: :name', ['name' => $name])]);
    }

    public function toggleActive($id)
    {
        $admin = AdminModel::findOrFail($id);

        // Prevent disabling the last active admin
        $activeAdminsCount = AdminModel::where('is_active', true)->count();
        if ($activeAdminsCount <= 1 && $admin->is_active) {
            $this->dispatch('toast', ['type' => 'error', 'message' => __('Cannot disable the last active admin')]);
            return;
        }

        $admin->is_active = ! $admin->is_active;
        $admin->save();

        $action = $admin->is_active ? __('Activate') : __('Deactivate');
        $this->dispatch('toast', ['type' => 'success', 'message' => __(':action Admin: :name', ['action' => $action, 'name' => $admin->name])]);
    }

    public function render()
    {
        $admins = AdminModel::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('username', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate($this->per_page);

        return view('livewire.dashboard.admins', [
            'admins' => $admins,
        ]);
    }
}
