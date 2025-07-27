<?php

namespace App\Http\Livewire\Admin\Management;

use Livewire\Component;
use App\Models\User;

class UserManagement extends Component
{
    public $users, $searchTerm;

    protected $rules = [
        'searchTerm' => 'nullable|string|min:3',
    ];

    public function mount()
    {
        $this->users = User::all();
    }

    public function searchUsers()
    {
        $this->validate();

        $this->users = User::where('name', 'like', '%' . $this->searchTerm . '%')->get();
    }

    public function render()
    {
        return view('livewire.admin.management.user-management');
    }
}
