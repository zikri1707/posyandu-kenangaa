<?php

namespace App\Http\Livewire\Admin\Management;

use Livewire\Component;
use App\Models\Schedule;

class ScheduleManagement extends Component
{
    public $schedules, $searchTerm;

    protected $rules = [
        'searchTerm' => 'nullable|string|min:3',
    ];

    public function mount()
    {
        $this->schedules = Schedule::all();
    }

    public function searchSchedules()
    {
        $this->validate();

        $this->schedules = Schedule::where('title', 'like', '%' . $this->searchTerm . '%')->get();
    }

    public function render()
    {
        return view('livewire.admin.management.schedule-management');
    }
}
