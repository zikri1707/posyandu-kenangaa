<?php

namespace App\Http\Controllers;

use App\Models\Pedukuhan;
use App\Http\Requests\PedukuhanRequest;
use Illuminate\Routing\Controller; // Ensure the correct base Controller is used

class PedukuhanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    public function index()
    {
        $pedukuhans = Pedukuhan::withCount('posyandus')->latest()->get();
        return view('admin.pedukuhan-management.index', compact('pedukuhans'));
    }

    public function create()
    {
        return view('admin.pedukuhan-management.create');
    }

    public function store(PedukuhanRequest $request)
    {
        Pedukuhan::create($request->validated());

        return redirect()->route('pedukuhans.index')
            ->with('success', 'Pedukuhan berhasil ditambahkan');
    }

    public function show(Pedukuhan $pedukuhan)
    {
        $pedukuhan->load('posyandus');
        return view('admin.pedukuhan-management.show', compact('pedukuhan'));
    }

    public function edit(Pedukuhan $pedukuhan)
    {
        return view('admin.pedukuhan-management.edit', compact('pedukuhan'));
    }

    public function update(PedukuhanRequest $request, Pedukuhan $pedukuhan)
    {
        $pedukuhan->update($request->validated());

        return redirect()->route('pedukuhans.index')
            ->with('success', 'Data pedukuhan berhasil diupdate');
    }

    public function destroy(Pedukuhan $pedukuhan)
    {
        if ($pedukuhan->posyandus()->exists()) {
            return back()->with('error', 'Tidak bisa menghapus pedukuhan yang masih memiliki posyandu');
        }

        $pedukuhan->delete();

        return back()->with('success', 'Pedukuhan berhasil dihapus');
    }
}