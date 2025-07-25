<?php


namespace App\Http\Livewire;

use App\Models\Pedukuhan;
use Livewire\Component;
use Livewire\WithPagination;

class PedukuhanManagement extends Component
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
        $pedukuhans = Pedukuhan::where('name', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.pedukuhan-management', compact('pedukuhans'));
    }
}
