<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;

class User extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $per_page = 10;
    public $search = '';
    protected $users;

    public function block($id)
    {
        $user = \App\Models\User::query()->find($id);
        $user->is_block = !$user->is_block;
        $user->save();
        $this->dispatch('toast', message: $user->is_block ? __('User blocked') : __('User unblocked'));
    }

    public function render()
    {
        $this->users = \App\Models\User::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->latest()->paginate($this->per_page);

        return view('livewire.dashboard.user', [
            'users' => $this->users
        ]);
    }
}
