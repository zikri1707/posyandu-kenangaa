<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\PedukuhanRequest;
use App\Models\Pedukuhan;
use Illuminate\Http\Request;

class PedukuhanController extends Controller
{
    public function index(Request $request)
    {
        $pedukuhans = Pedukuhan::withCount(['posyandus'])
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->paginate(10)
            ->withQueryString();

        return view('livewire.admin.pedukuhan-management.index', compact('pedukuhans'));
    }

    public function create()
    {
        // Menampilkan formulir untuk membuat Pedukuhan baru
        return view('livewire.admin.pedukuhan-management.create');
    }

    public function store(PedukuhanRequest $request, \App\Services\PedukuhanService $pedukuhanService)
    {
        // Menambah data Pedukuhan baru
        $pedukuhanService->createPedukuhan($request->validated());

        return redirect()->route('admin.pedukuhans.index')->with('success', 'Pedukuhan berhasil ditambahkan.');
    }

    public function show(Pedukuhan $pedukuhan)
    {
        // Menampilkan detail dari Pedukuhan
        return view('livewire.admin.pedukuhan-management.details', compact('pedukuhan'));
    }

    public function edit(Pedukuhan $pedukuhan)
    {
        // Menampilkan formulir untuk mengedit data Pedukuhan
        return view('livewire.admin.pedukuhan-management.update', compact('pedukuhan'));
    }

    public function update(PedukuhanRequest $request, Pedukuhan $pedukuhan, \App\Services\PedukuhanService $pedukuhanService)
    {
        // Memperbarui data Pedukuhan
        $pedukuhanService->updatePedukuhan($pedukuhan, $request->validated());

        return redirect()->route('admin.pedukuhans.index')->with('success', 'Pedukuhan berhasil diperbarui.');
    }

    public function destroy(Pedukuhan $pedukuhan, \App\Services\PedukuhanService $pedukuhanService)
    {
        // Menghapus data Pedukuhan
        $pedukuhanService->deletePedukuhan($pedukuhan);

        return redirect()->route('admin.pedukuhans.index')->with('success', 'Pedukuhan berhasil dihapus.');
    }
}
