<?php

namespace App\Livewire\Admin\Management;

use App\Models\Posyandu;
use App\Livewire\Shared\BaseAdminComponent;

class PosyanduManagement extends BaseAdminComponent
{
    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function render()
    {
        $posyandus = Posyandu::with('pedukuhan')
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('unique_code', 'like', '%' . $this->search . '%')
                  ->orWhere('address', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.posyandu-management.index', [
            'posyandus' => $posyandus,
        ]);
    }
}
