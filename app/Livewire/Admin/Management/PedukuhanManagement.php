<?php

namespace App\Livewire\Admin\Management;

use App\Livewire\Shared\BaseAdminComponent;
use App\Models\Pedukuhan;

class PedukuhanManagement extends BaseAdminComponent
{
    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function render()
    {
        $query = Pedukuhan::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            })
            ->latest();

        return view('livewire.admin.pedukuhan-management.index', [
            'pedukuhans' => $query->paginate(10),
        ]);
    }
}
