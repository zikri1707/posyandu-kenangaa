<?php

namespace App\Http\Livewire\Admin\Management;

use Livewire\Component;
use App\Models\MedicalRecord;

class MedicalRecordManagement extends Component
{
    public $medicalRecords, $searchTerm;

    protected $rules = [
        'searchTerm' => 'nullable|string|min:3',
    ];

    public function mount()
    {
        $this->medicalRecords = MedicalRecord::all();
    }

    public function searchMedicalRecords()
    {
        $this->validate();

        $this->medicalRecords = MedicalRecord::where('diagnosis', 'like', '%' . $this->searchTerm . '%')->get();
    }

    public function render()
    {
        return view('livewire.admin.management.medical-record-management');
    }
}
