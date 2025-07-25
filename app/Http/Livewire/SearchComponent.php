<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SearchComponent extends Component
{
    public $query = '';

    public function updatedQuery()
    {
        $this->emit('searchUpdated', $this->query);
    }

    public function render()
    {
        return view('livewire.search-component');
    }
}
