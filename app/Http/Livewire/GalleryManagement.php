<?php

namespace App\Http\Livewire;

use App\Models\Gallery;
use Livewire\Component;
use Livewire\WithPagination;

class GalleryManagement extends Component
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
        $galleries = Gallery::where('title', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.gallery-management', compact('galleries'));
    }
}
