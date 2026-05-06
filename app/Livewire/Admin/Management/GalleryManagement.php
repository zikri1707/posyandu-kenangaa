<?php

namespace App\Livewire\Admin\Management;

use App\Models\Gallery;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin-layout')]
class GalleryManagement extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Gallery::query()
            ->when($this->search, function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%');
            })
            ->latest();

        return view('livewire.admin.gallery-management.index', [
            'galleries' => $query->paginate(12),
        ]);
    }
}
