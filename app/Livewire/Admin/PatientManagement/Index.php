<?php

namespace App\Livewire\Admin\PatientManagement;

use App\Livewire\Shared\BaseAdminComponent;
use App\Models\Patient;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
class Index extends BaseAdminComponent
{
    public string $search = '';

    public string $status = 'all';

    public string $category = 'all';

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    public bool $showDeleteModal = false;

    public ?int $selectedId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
        'category' => ['except' => 'all'],
    ];

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'category', 'status']);
        $this->resetPage();
    }

    public function sortByField(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->showDeleteModal = true;
    }

    public function closeModal(): void
    {
        $this->showDeleteModal = false;
        $this->selectedId = null;
    }

    public function deletePatient(): void
    {
        if ($this->selectedId) {
            $patient = Patient::find($this->selectedId);
            if ($patient) {
                $this->authorize('delete', $patient);
                $patient->delete();
                $this->notify('Data warga berhasil dihapus.');
            }
        }
        $this->closeModal();
    }

    public function render()
    {
        // Terapkan scope posyandu secara otomatis
        $query = $this->applyPosyanduScope(Patient::with('posyandu'))
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('full_name', 'like', '%'.$this->search.'%')
                        ->orWhere('id_number_hash', Patient::generateBlindIndex($this->search));
                });
            })
            ->when($this->category !== 'all', fn ($q) => $q->where('category', $this->category))
            ->orderBy($this->sortBy === 'name' ? 'full_name' : $this->sortBy, $this->sortDirection);

        return view('livewire.admin.patient-management.index', [
            'patients' => $query->paginate(10),
        ])->title('Data Warga Terdaftar');
    }
}
