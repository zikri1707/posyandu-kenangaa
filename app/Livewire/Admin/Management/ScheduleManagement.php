<?php

namespace App\Livewire\Admin\Management;

use App\Livewire\Shared\BaseAdminComponent;
use App\Models\Posyandu;
use App\Models\Schedule;
use App\Services\ScheduleService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

/**
 * Komponen Manajemen Jadwal (OOP & Clean Code).
 * Menangani tampilan daftar, filtering, dan penghapusan jadwal.
 */
#[Layout('layouts.admin-layout')]
class ScheduleManagement extends BaseAdminComponent
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $status = '';

    #[Url(except: '')]
    public $posyandu_id = '';

    // Form properties for Create
    public string $title = '';

    public string $description = '';

    public string $start_time = '';

    public string $end_time = '';

    public string $location = '';

    public string $new_status = 'upcoming';

    public ?int $selected_posyandu_id = null;

    public bool $showCreateModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'posyandu_id' => ['except' => ''],
    ];

    public function mount(): void
    {
        $user = Auth::user();
        if (! $user->isSuperAdmin()) {
            $this->selected_posyandu_id = $user->posyandu_id;
        }
    }

    /**
     * Simpan jadwal baru.
     */
    public function save(ScheduleService $service)
    {
        $this->authorize('create', Schedule::class);

        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required|string|max:255',
            'new_status' => 'required|in:upcoming,ongoing,completed',
            'selected_posyandu_id' => 'required|exists:posyandus,id',
        ]);

        // Map UI names to model names
        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'location' => $this->location,
            'status' => $this->new_status,
            'posyandu_id' => $this->selected_posyandu_id,
        ];

        $service->createSchedule($data, Auth::user());

        $this->reset(['title', 'description', 'start_time', 'end_time', 'location', 'new_status', 'showCreateModal']);
        $this->notify('Jadwal baru berhasil ditambahkan.');
    }

    /**
     * Render halaman manajemen jadwal.
     */
    public function render(): View
    {
        $now = now();
        $baseQuery = $this->applyPosyanduScope(Schedule::with('posyandu'));

        $schedules = (clone $baseQuery)
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->posyandu_id, fn ($q) => $q->where('posyandu_id', $this->posyandu_id))
            ->orderBy('start_time', 'asc')
            ->paginate(10);

        return view('livewire.admin.schedule-management.index', [
            'schedules' => $schedules,
            'stats' => $this->getStats($baseQuery),
            'agendaTerdekat' => $this->getUpcomingAgenda($baseQuery),
            'posyandus' => $this->getAllowedPosyandus(),
        ]);
    }

    private function getStats(Builder $query): array
    {
        $now = now();
        $stats = (clone $query)
            ->selectRaw('COUNT(CASE WHEN MONTH(start_time) = ? AND YEAR(start_time) = ? THEN 1 END) as total_month', [$now->month, $now->year])
            ->selectRaw('COUNT(CASE WHEN status = "completed" THEN 1 END) as completed')
            ->selectRaw('COUNT(CASE WHEN status = "upcoming" THEN 1 END) as upcoming')
            ->selectRaw('COUNT(CASE WHEN status = "ongoing" THEN 1 END) as ongoing')
            ->first();

        return [
            'total_month' => $stats->total_month ?? 0,
            'completed' => $stats->completed ?? 0,
            'upcoming' => $stats->upcoming ?? 0,
            'ongoing' => $stats->ongoing ?? 0,
        ];
    }

    private function getUpcomingAgenda(Builder $query): ?Schedule
    {
        return (clone $query)
            ->where('status', 'upcoming')
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->first();
    }

    /**
     * Hapus jadwal dengan autorisasi.
     */
    public function deleteSchedule(int $id, ScheduleService $service): void
    {
        $schedule = Schedule::findOrFail($id);
        $this->authorize('delete', $schedule);

        $service->deleteSchedule($schedule);
        $this->notify('Jadwal kegiatan berhasil dihapus.');
    }
}
