<?php

namespace App\Http\Controllers;

use App\Models\Posyandu;
use App\Models\Pedukuhan;
use App\Http\Requests\PosyanduRequest;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controller as BaseController;

class PosyanduController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $posyandus = Posyandu::with('pedukuhan')->latest()->get();
        return view('admin.posyandu-management.index', compact('posyandus'));
    }

    public function create()
    {
        $pedukuhans = Pedukuhan::all();
        return view('admin.posyandu-management.create', compact('pedukuhans'));
    }

    public function store(PosyanduRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('logo_photo')) {
            $data['logo_photo'] = $request->file('logo_photo')->store('posyandu-logos');
        }

        Posyandu::create($data);

        return redirect()->route('posyandus.index')
            ->with('success', 'Posyandu berhasil didaftarkan');
    }

    public function show(Posyandu $posyandu)
    {
        $posyandu->load(['pedukuhan', 'schedules', 'patients']);
        return view('admin.posyandu-management.show', compact('posyandu'));
    }

    public function edit(Posyandu $posyandu)
    {
        $pedukuhans = Pedukuhan::all();
        return view('admin.posyandu-management.edit', compact('posyandu', 'pedukuhans'));
    }

    public function update(PosyanduRequest $request, Posyandu $posyandu)
    {
        $data = $request->validated();
        
        if ($request->hasFile('logo_photo')) {
            Storage::delete($posyandu->logo_photo);
            $data['logo_photo'] = $request->file('logo_photo')->store('posyandu-logos');
        }

        $posyandu->update($data);

        return redirect()->route('posyandus.index')
            ->with('success', 'Data posyandu berhasil diupdate');
    }

    public function destroy(Posyandu $posyandu)
    {
        if ($posyandu->logo_photo) {
            Storage::delete($posyandu->logo_photo);
        }
        
        $posyandu->delete();

        return back()->with('success', 'Posyandu berhasil dihapus');
    }

    public function generateReport(Posyandu $posyandu)
    {
        $pdf = Pdf::loadView('exports.posyandu-report', compact('posyandu'));
        return $pdf->download("posyandu-report-{$posyandu->id}.pdf");
    }
}
