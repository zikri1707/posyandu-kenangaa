<?php

namespace App\Livewire;

use Livewire\Component;

class GlobalSearch extends Component
{
    public $search = '';

    public function render()
    {
        $results = [
            'patients' => [],
            'schedules' => [],
            'articles' => []
        ];

        if (strlen($this->search) >= 2) {
            $results['patients'] = \App\Models\Patient::where('full_name', 'like', '%' . $this->search . '%')
                ->orWhere('id_number', 'like', '%' . $this->search . '%')
                ->limit(5)->get();

            $results['schedules'] = \App\Models\Schedule::where('title', 'like', '%' . $this->search . '%')
                ->orWhere('location', 'like', '%' . $this->search . '%')
                ->limit(3)->get();

            $results['articles'] = \App\Models\Article::where('title', 'like', '%' . $this->search . '%')
                ->limit(3)->get();
        }

        return view('livewire.global-search', [
            'results' => $results
        ]);
    }
}
