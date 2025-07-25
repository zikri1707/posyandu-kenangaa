<?php

namespace App\Http\Livewire;

use App\Models\MedicalRecord;
use Livewire\Component;
use Livewire\WithPagination;

class MedicalRecordManagement extends Component
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
        $medicalRecords = MedicalRecord::where('diagnosis', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.medical-record-management', compact('medicalRecords'));
    }
}
