<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Posyandu;
use App\Http\Requests\ScheduleRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $schedules = Schedule::with(['posyandu', 'user'])->latest()->get();
        return view('admin.schedule-management.index', compact('schedules'));
    }

    public function create()
    {
        $posyandus = Posyandu::all();
        return view('admin.schedule-management.create', compact('posyandus'));
    }

    public function store(ScheduleRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;

        Schedule::create($data);

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal berhasil dibuat');
    }

    public function show(Schedule $schedule)
    {
        return view('admin.schedule-management.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $posyandus = Posyandu::all();
        return view('admin.schedule-management.edit', compact('schedule', 'posyandus'));
    }

    public function update(ScheduleRequest $request, Schedule $schedule)
    {
        $schedule->update($request->validated());

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal berhasil diupdate');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return back()->with('success', 'Jadwal berhasil dihapus');
    }

    public function updateStatus(Request $request, Schedule $schedule)
    {
        $request->validate(['status' => 'required|in:active,cancelled,completed']);

        $schedule->update(['status' => $request->status]);

        return back()->with('success', 'Status jadwal diupdate');
    }
}