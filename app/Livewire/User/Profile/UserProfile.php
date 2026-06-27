<?php

namespace App\Livewire\User\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserProfile extends Component
{
    public string $name = '';

    public string $email = '';

    protected function rules(): array
    {
        return [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ];
    }

    public function mount(): void
    {
        /** @var User $user */
        $user        = Auth::user();
        $this->name  = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile(): void
    {
        $this->validate();

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'name'  => $this->name,
            'email' => $this->email,
        ]);

        session()->flash('status', 'Profile updated successfully.');
    }

    public function render()
    {
        return view('livewire.user.profile.user-profile');
    }
}
