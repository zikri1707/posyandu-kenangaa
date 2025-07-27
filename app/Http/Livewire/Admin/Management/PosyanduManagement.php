<?php

namespace App\Http\Livewire\Admin\Management;

use Livewire\Component;
use App\Models\Posyandu;

class PosyanduManagement extends Component
{
    public $posyandus, $searchTerm;

    protected $rules = [
        'searchTerm' => 'nullable|string|min:3',
    ];

    public function mount()
    {
        $this->posyandus = Posyandu::all();
    }

    public function searchPosyandus()
    {
        $this->validate();

        $this->posyandus = Posyandu::where('name', 'like', '%' . $this->searchTerm . '%')->get();
    }

    public function render()
    {
        return view('livewire.admin.management.posyandu-management');
    }
}
