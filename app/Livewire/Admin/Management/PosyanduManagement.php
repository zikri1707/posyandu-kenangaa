<?php

namespace App\Livewire\Admin\Management;

use App\Livewire\Shared\BaseAdminComponent;
use App\Models\Posyandu;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin-layout')]
class PosyanduManagement extends BaseAdminComponent
{
    public string $search = '';

    public bool $showDeleteModal = false;

    public ?int $selectedId = null;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

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

    public function deletePosyandu(): void
    {
        if ($this->selectedId) {
            $posyandu = Posyandu::find($this->selectedId);
            if ($posyandu) {
                // Check if posyandu has active data (optional but recommended for safety)
                if ($posyandu->users()->count() > 0 || $posyandu->patients()->count() > 0) {
                    $this->notify('Tidak bisa menghapus Posyandu yang masih memiliki data Kader atau Warga.', 'error');
                } else {
                    $posyandu->delete();
                    $this->notify('Data Posyandu berhasil dihapus.');
                }
            }
        }
        $this->closeModal();
    }

    public function render()
    {
        $posyandus = Posyandu::withCount([
                'patients',
                'patients as balita_count'    => fn ($q) => $q->where('category', 'balita'),
                'patients as ibu_hamil_count' => fn ($q) => $q->where('category', 'ibu_hamil'),
                'patients as lansia_count'    => fn ($q) => $q->where('category', 'lansia'),
            ])
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('unique_code', 'like', '%'.$this->search.'%')
                    ->orWhere('address', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.posyandu-management.index', [
            'posyandus'      => $posyandus,
            'totalPosyandu'  => $posyandus->total(),
            'totalWarga'     => \App\Models\Patient::count(),
            'totalBalita'    => \App\Models\Patient::where('category', 'balita')->count(),
            'totalBumil'     => \App\Models\Patient::where('category', 'ibu_hamil')->count(),
            'totalLansia'    => \App\Models\Patient::where('category', 'lansia')->count(),
        ]);
    }
}
