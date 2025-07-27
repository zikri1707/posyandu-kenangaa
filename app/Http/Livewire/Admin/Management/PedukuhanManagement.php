<?php

namespace App\Http\Livewire\Admin\Management;

use Livewire\Component;
use App\Models\Pedukuhan;

class PedukuhanManagement extends Component
{
    public $pedukuhans, $searchTerm;

    protected $rules = [
        'searchTerm' => 'nullable|string|min:3',
    ];

    public function mount()
    {
        $this->pedukuhans = Pedukuhan::all();
    }

    public function searchPedukuhans()
    {
        $this->validate();

        $this->pedukuhans = Pedukuhan::where('name', 'like', '%' . $this->searchTerm . '%')->get();
    }

    public function render()
    {
        return view('livewire.admin.management.pedukuhan-management');
    }
}
