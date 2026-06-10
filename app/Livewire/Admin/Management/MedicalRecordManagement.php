<?php

namespace App\Livewire\Admin\Management;

use App\Livewire\Shared\BaseAdminComponent;
use App\Models\MedicalRecord;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin-layout')]
class MedicalRecordManagement extends BaseAdminComponent
{
    public string $search = '';

    public string $posyandu_id = '';

    public string $sortBy = 'visit_date_desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'posyandu_id' => ['except' => ''],
        'sortBy' => ['except' => 'visit_date_desc'],
    ];

    public function render()
    {
        // Gunakan applyPosyanduScope dari Trait
        $query = $this->applyPosyanduScope(MedicalRecord::with(['patient.posyandu', 'user']))
            ->when($this->search, function ($q) {
                $q->whereHas('patient', function ($sq) {
                    $sq->where('full_name', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->posyandu_id, function ($q) {
                $q->whereHas('patient', function ($sq) {
                    $sq->where('posyandu_id', $this->posyandu_id);
                });
            });

        // Apply sorting
        $query = match($this->sortBy) {
            'patient_name_asc' => $query->join('patients', 'medical_records.patient_id', '=', 'patients.id')
                ->orderBy('patients.full_name', 'asc')
                ->select('medical_records.*'),
            'patient_name_desc' => $query->join('patients', 'medical_records.patient_id', '=', 'patients.id')
                ->orderBy('patients.full_name', 'desc')
                ->select('medical_records.*'),
            'visit_date_asc' => $query->orderBy('visit_date', 'asc'),
            'visit_date_desc' => $query->orderBy('visit_date', 'desc'),
            'updated_at_asc' => $query->orderBy('updated_at', 'asc'),
            'updated_at_desc' => $query->orderBy('updated_at', 'desc'),
            default => $query->latest('visit_date'),
        };

        return view('livewire.admin.medical-record-management.index', [
            'medicalRecords' => $query->paginate(10),
            'sortBy' => $this->sortBy,
        ]);
    }
}
