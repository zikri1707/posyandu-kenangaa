<?php

namespace App\Http\Livewire;

use App\Models\Schedule;
use Livewire\Component;
use Livewire\WithPagination;

class ScheduleManagement extends Component
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
        $schedules = Schedule::where('title', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.schedule-management', compact('schedules'));
    }
}
