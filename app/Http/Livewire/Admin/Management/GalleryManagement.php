<?php

namespace App\Http\Livewire\Admin\Management;

use Livewire\Component;
use App\Models\Gallery;

class GalleryManagement extends Component
{
    public $galleries, $searchTerm;

    protected $rules = [
        'searchTerm' => 'nullable|string|min:3',
    ];

    public function mount()
    {
        $this->galleries = Gallery::all();
    }

    public function searchGalleries()
    {
        $this->validate();

        $this->galleries = Gallery::where('title', 'like', '%' . $this->searchTerm . '%')->get();
    }

    public function render()
    {
        return view('livewire.admin.management.gallery-management');
    }
}
