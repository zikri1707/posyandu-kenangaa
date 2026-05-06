<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\PosyanduRequest;
use App\Models\Posyandu;
use Illuminate\Http\Request;

class PosyanduController extends Controller
{
    public function index(Request $request)
    {
        $posyandus = Posyandu::with('pedukuhan')
            ->when($request->search, fn ($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('unique_code', 'like', "%{$s}%")
                    ->orWhere('address', 'like', "%{$s}%");
            }))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('livewire.admin.posyandu-management.index', compact('posyandus'));
    }

    public function create()
    {
        $pedukuhans = \App\Models\Pedukuhan::orderBy('name')->get();

        return view('livewire.admin.posyandu-management.create', compact('pedukuhans'));
    }

    public function store(PosyanduRequest $request, \App\Services\PosyanduService $posyanduService)
    {
        $posyanduService->createPosyandu($request->validated());

        return redirect()->route('admin.posyandu.index')
            ->with('success', 'Posyandu berhasil ditambahkan.');
    }

    public function show(Posyandu $posyandu)
    {
        $posyandu->load('pedukuhan');

        return view('livewire.admin.posyandu-management.details', compact('posyandu'));
    }

    public function edit(Posyandu $posyandu)
    {
        $pedukuhans = \App\Models\Pedukuhan::orderBy('name')->get();

        return view('livewire.admin.posyandu-management.update', compact('posyandu', 'pedukuhans'));
    }

    public function update(PosyanduRequest $request, Posyandu $posyandu, \App\Services\PosyanduService $posyanduService)
    {
        $posyanduService->updatePosyandu($posyandu, $request->validated());

        return redirect()->route('admin.posyandu.index')
            ->with('success', 'Posyandu berhasil diperbarui.');
    }

    public function destroy(Posyandu $posyandu, \App\Services\PosyanduService $posyanduService)
    {
        $posyanduService->deletePosyandu($posyandu);

        return redirect()->route('admin.posyandu.index')
            ->with('success', 'Posyandu berhasil dihapus.');
    }
}
