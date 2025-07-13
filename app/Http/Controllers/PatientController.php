<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Posyandu;
use App\Http\Requests\PatientRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;

class PatientController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth', 'medical']);
    }

    public function index()
    {
        $patients = Patient::with('posyandu')->latest()->get();
        return view('admin.patient-management.index', compact('patients'));
    }

    public function create()
    {
        $posyandus = Posyandu::all();
        return view('admin.patient-management.create', compact('posyandus'));
    }

    public function store(PatientRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('patients');
        }

        Patient::create($data);

        return redirect()->route('patients.index')
            ->with('success', 'Pasien berhasil didaftarkan');
    }

    public function show(Patient $patient)
    {
        $medicalRecords = $patient->medicalRecords()->latest()->get();
        return view('admin.patient-management.show', compact('patient', 'medicalRecords'));
    }

    public function edit(Patient $patient)
    {
        $posyandus = Posyandu::all();
        return view('admin.patient-management.edit', compact('patient', 'posyandus'));
    }

    public function update(PatientRequest $request, Patient $patient)
    {
        $data = $request->validated();
        
        if ($request->hasFile('profile_photo')) {
            Storage::delete($patient->profile_photo);
            $data['profile_photo'] = $request->file('profile_photo')->store('patients');
        }

        $patient->update($data);

        return redirect()->route('patients.index')
            ->with('success', 'Data pasien berhasil diupdate');
    }

    public function destroy(Patient $patient)
    {
        if ($patient->profile_photo) {
            Storage::delete($patient->profile_photo);
        }
        
        $patient->delete();

        return back()->with('success', 'Pasien berhasil dihapus');
    }

    public function exportMedicalHistory(Patient $patient)
    {
        $records = $patient->medicalRecords;
        $pdf = Pdf::loadView('exports.medical-history', compact('patient', 'records'));
        return $pdf->download("medical-history-{$patient->id}.pdf");
    }
}
