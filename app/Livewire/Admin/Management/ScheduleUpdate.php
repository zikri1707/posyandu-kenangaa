<?php

namespace App\Livewire\Admin\Management;

use App\Models\Posyandu;
use App\Models\Schedule;
use App\Services\ScheduleService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Komponen untuk memperbarui jadwal (OOP & Clean Code).
 */
#[Layout('layouts.admin-layout')]
class ScheduleUpdate extends Component
{
    public Schedule $schedule;

    // Form fields
    public string $title = '';

    public string $description = '';

    public string $start_time = '';

    public string $end_time = '';

    public string $location = '';

    public string $status = '';

    public ?int $posyandu_id = null;

    /**
     * Inisialisasi data jadwal ke dalam property.
     */
    public function mount(Schedule $schedule): void
    {
        $this->schedule = $schedule;
        $this->authorize('update', $schedule);

        $this->title = $schedule->title;
        $this->description = $schedule->description ?? '';
        $this->start_time = $schedule->start_time->format('Y-m-d\TH:i');
        $this->end_time = $schedule->end_time ? $schedule->end_time->format('Y-m-d\TH:i') : '';
        $this->location = $schedule->location ?? '';
        $this->status = $schedule->status;
        $this->posyandu_id = $schedule->posyandu_id;
    }

    /**
     * Aturan validasi.
     */
    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required|string|max:255',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'posyandu_id' => 'required|exists:posyandus,id',
        ];
    }

    /**
     * Update jadwal menggunakan Service.
     */
    public function save(ScheduleService $service)
    {
        $validated = $this->validate();

        $service->updateSchedule($this->schedule, $validated);

        session()->flash('success', 'Jadwal kegiatan berhasil diperbarui.');

        return redirect()->route('admin.schedules.index');
    }

    /**
     * Render view.
     */
    public function render(): View
    {
        $user = Auth::user();
        $posyandus = $user->isSuperAdmin()
            ? Posyandu::orderBy('name')->get()
            : Posyandu::where('id', $user->posyandu_id)->get();

        return view('livewire.admin.schedule-management.update', [
            'posyandus' => $posyandus,
        ]);
    }
}
