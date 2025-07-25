<?php

namespace App\Http\Livewire;

use App\Models\Patient;
use Livewire\Component;
use Livewire\WithPagination;

class PatientManagement extends Component
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
        $patients = Patient::where('full_name', 'like', '%' . $this->search . '%')
            ->paginate(10);
        
        return view('livewire.patient-management', compact('patients'));
    }
}
