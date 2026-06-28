<?php

namespace App\Livewire\Admin\Management;

use App\Models\Posyandu;
use App\Models\Schedule;
use App\Models\User;
use App\Services\ScheduleService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Komponen untuk membuat jadwal baru (OOP & Clean Code).
 */
#[Layout('layouts.admin-layout')]
class ScheduleCreate extends Component
{
    public string $title = '';

    public string $description = '';

    public string $start_time = '';

    public string $end_time = '';

    public string $location = '';

    public string $status = 'upcoming';

    public ?int $posyandu_id = null;

    /**
     * Inisialisasi komponen.
     */
    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();
        if (! $user->isSuperAdmin()) {
            $this->posyandu_id = $user->posyandu_id;
        }
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
     * Simpan jadwal baru menggunakan Service.
     */
    public function save(ScheduleService $service)
    {
        Gate::authorize('create', Schedule::class);
        $validated = $this->validate();

        /** @var User $user */
        $user = Auth::user();
        $service->createSchedule($validated, $user);

        session()->flash('success', 'Jadwal kegiatan berhasil ditambahkan.');

        return redirect()->route('admin.schedules.index');
    }

    /**
     * Render view.
     */
    public function render(): View
    {
        /** @var User $user */
        $user     = Auth::user();
        $posyandus = $user->isSuperAdmin()
            ? Posyandu::orderBy('name')->get()
            : Posyandu::where('id', $user->posyandu_id)->get();

        return view('livewire.admin.schedule-management.create', [
            'posyandus' => $posyandus,
        ]);
    }
}
