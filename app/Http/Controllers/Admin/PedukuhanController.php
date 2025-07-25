<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pedukuhan;
use App\Http\Requests\PedukuhanRequest;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PedukuhanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    public function index(): View
    {
        $pedukuhans = Pedukuhan::withCount('posyandus')->latest()->get();
        return view('admin.pedukuhan-management.index', compact('pedukuhans'));
    }

    public function create(): View
    {
        return view('admin.pedukuhan-management.create');
    }

    public function store(PedukuhanRequest $request): RedirectResponse
    {
        Pedukuhan::create($request->validated());

        return redirect()->route('pedukuhans.index')
            ->with('success', 'Pedukuhan berhasil ditambahkan');
    }

    public function show(Pedukuhan $pedukuhan): View
    {
        $pedukuhan->load('posyandus');
        return view('admin.pedukuhan-management.show', compact('pedukuhan'));
    }

    public function edit(Pedukuhan $pedukuhan): View
    {
        return view('admin.pedukuhan-management.edit', compact('pedukuhan'));
    }

    public function update(PedukuhanRequest $request, Pedukuhan $pedukuhan): RedirectResponse
    {
        $pedukuhan->update($request->validated());

        return redirect()->route('pedukuhans.index')
            ->with('success', 'Data pedukuhan berhasil diupdate');
    }

    public function destroy(Pedukuhan $pedukuhan): RedirectResponse
    {
        if ($pedukuhan->posyandus()->exists()) {
            return back()->with('error', 'Tidak bisa menghapus pedukuhan yang masih memiliki posyandu');
        }

        $pedukuhan->delete();

        return back()->with('success', 'Pedukuhan berhasil dihapus');
    }
}
