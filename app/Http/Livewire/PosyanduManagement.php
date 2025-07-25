<?php

namespace App\Http\Livewire;

use App\Models\Posyandu;
use Livewire\Component;
use Livewire\WithPagination;

class PosyanduManagement extends Component
{
    use WithPagination;

    public $search = '';

    protected $rules = [
        'search' => 'nullable|string|min:3',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $posyandus = Posyandu::where('name', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.posyandu-management', compact('posyandus'));
    }
}
