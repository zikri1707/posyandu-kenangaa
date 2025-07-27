<?php

namespace App\Http\Livewire\Admin\Management;

use Livewire\Component;
use App\Models\Patient;

class PatientManagement extends Component
{
    public $patients, $searchTerm;

    protected $rules = [
        'searchTerm' => 'nullable|string|min:3',
    ];

    public function mount()
    {
        $this->patients = Patient::all();
    }

    public function searchPatients()
    {
        $this->validate();

        $this->patients = Patient::where('full_name', 'like', '%' . $this->searchTerm . '%')->get();
    }

    public function render()
    {
        return view('livewire.admin.management.patient-management');
    }
}
