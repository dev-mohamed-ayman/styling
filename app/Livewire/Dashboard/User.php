<?php

namespace App\Livewire\Dashboard;

use App\Models\User as UserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class User extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $per_page = 10;

    public $search = '';

    // Modal properties
    public $is_open = false;

    public $user_id = null;

    public $name = '';

    public $email = '';

    public $phone = '';

    public $password = '';

    public $image = null;

    public $old_image = null;

    public $is_block = false;

    public $gender = null;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,'.$this->user_id,
            'phone' => 'nullable|string|unique:users,phone,'.$this->user_id,
            'password' => $this->user_id ? 'nullable|min:6' : 'required|min:6',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_block' => 'boolean',
            'gender' => 'nullable|in:male,female',
        ];

        return $rules;
    }

    protected $messages = [
        'name.required' => 'The name field is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already taken.',
        'phone.unique' => 'This phone number is already taken.',
        'password.required' => 'The password field is required.',
        'password.min' => 'The password must be at least 6 characters.',
        'image.image' => 'The file must be an image.',
        'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
        'image.max' => 'The image may not be greater than 2MB.',
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
        $this->user_id = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->password = '';
        $this->image = null;
        $this->old_image = null;
        $this->is_block = false;
        $this->gender = null;
    }

    public function create()
    {
        $this->openModal();
    }

    public function edit($id)
    {
        $user = UserModel::findOrFail($id);
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->old_image = $user->image;
        $this->is_block = $user->is_block;
        $this->gender = $user->gender;
        $this->password = '';
        $this->is_open = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_block' => $this->is_block,
            'gender' => $this->gender,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->image) {
            $data['image'] = $this->image->store('users', 'public');
        }

        $user = UserModel::create($data);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Create User: :name', ['name' => $user->name])]);
    }

    public function update()
    {
        $this->validate();

        $user = UserModel::findOrFail($this->user_id);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_block' => $this->is_block,
            'gender' => $this->gender,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->image) {
            // Delete old image if exists
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $data['image'] = $this->image->store('users', 'public');
        }

        $user->update($data);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Edit User: :name', ['name' => $user->name])]);
    }

    public function delete($id)
    {
        $user = UserModel::findOrFail($id);

        // Delete image if exists
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        $name = $user->name;
        $user->delete();

        $this->dispatch('toast', ['type' => 'success', 'message' => __('Delete User: :name', ['name' => $name])]);
    }

    public function block($id)
    {
        $user = UserModel::findOrFail($id);
        $user->is_block = ! $user->is_block;
        $user->save();
        $action = $user->is_block ? __('Block') : __('Unblock');
        $this->dispatch('toast', ['type' => 'success', 'message' => __(':action User: :name', ['action' => $action, 'name' => $user->name])]);
    }

    public function render()
    {
        $users = UserModel::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('phone', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate($this->per_page);

        return view('livewire.dashboard.user', [
            'users' => $users,
        ]);
    }
}
