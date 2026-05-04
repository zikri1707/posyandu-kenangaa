<?php

namespace App\Livewire\Admin\Management;

use App\Models\MedicalRecord;
use App\Livewire\Shared\BaseAdminComponent;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin-layout')]
class MedicalRecordManagement extends BaseAdminComponent
{
    public string $search = '';
    public string $posyandu_id = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'posyandu_id' => ['except' => ''],
    ];

    public function render()
    {
        // Gunakan applyPosyanduScope dari Trait
        $query = $this->applyPosyanduScope(MedicalRecord::with(['patient.posyandu', 'user']))
            ->when($this->search, function($q) {
                $q->whereHas('patient', function($sq) {
                    $sq->where('full_name', 'like', '%' . $this->search . '%');
                    // NIK (id_number) is encrypted, standard LIKE won't work in DB.
                });
            })
            ->when($this->posyandu_id, function($q) {
                $q->whereHas('patient', function($sq) {
                    $sq->where('posyandu_id', $this->posyandu_id);
                });
            })
            ->latest('visit_date');

        return view('livewire.admin.medical-record-management.index', [
            'medicalRecords' => $query->paginate(10),
        ]);
    }
}
