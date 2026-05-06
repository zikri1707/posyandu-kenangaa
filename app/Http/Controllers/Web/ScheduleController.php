<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleRequest;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Schedule::with('posyandu')->accessibleBy($user)
            ->when($request->search, function ($q, $search) {
                return $q->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($q, $status) {
                return $q->where('status', $status);
            });

        // Statistik bulan ini
        $now = now();
        $statsQuery = clone $query;
        $totalBulanIni = (clone $statsQuery)
            ->whereMonth('start_time', $now->month)
            ->whereYear('start_time', $now->year)
            ->count();
        $selesai = (clone $statsQuery)->where('status', 'completed')->count();
        $mendatang = (clone $statsQuery)->where('status', 'upcoming')->count();

        // Agenda terdekat (upcoming berikutnya)
        $agendaTerdekat = (clone $query)
            ->where('status', 'upcoming')
            ->where('start_time', '>=', $now)
            ->orderBy('start_time')
            ->first();

        $schedules = $query->orderBy('start_time', 'asc')->paginate(10)->withQueryString();

        return view('livewire.admin.schedule-management.index', compact(
            'schedules', 'totalBulanIni', 'selesai', 'mendatang', 'agendaTerdekat'
        ));
    }

    public function create()
    {
        $user = auth()->user();

        // Superadmin bisa pilih semua posyandu, lainnya hanya posyandu mereka
        if ($user->isSuperAdmin()) {
            $posyandus = \App\Models\Posyandu::orderBy('name')->get();
        } else {
            $posyandus = \App\Models\Posyandu::where('id', $user->posyandu_id)->get();
        }

        return view('livewire.admin.schedule-management.create', compact('posyandus'));
    }

    public function store(ScheduleRequest $request, \App\Services\ScheduleService $scheduleService)
    {
        $scheduleService->createSchedule($request->validated(), auth()->user());

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal kegiatan berhasil ditambahkan.');
    }

    public function show(Schedule $schedule)
    {
        return view('livewire.admin.schedule-management.details', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $posyandus = \App\Models\Posyandu::all();

        return view('livewire.admin.schedule-management.update', compact('schedule', 'posyandus'));
    }

    public function update(ScheduleRequest $request, Schedule $schedule, \App\Services\ScheduleService $scheduleService)
    {
        $scheduleService->updateSchedule($schedule, $request->validated());

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule, \App\Services\ScheduleService $scheduleService)
    {
        $scheduleService->deleteSchedule($schedule);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
