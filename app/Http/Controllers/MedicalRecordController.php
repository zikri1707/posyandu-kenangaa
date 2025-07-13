<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Http\Requests\MedicalRecordRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'medical']);
    }

    public function index(Patient $patient)
    {
        $records = $patient->medicalRecords()->latest()->get();
        return view('admin.medical-record-management.index', compact('patient', 'records'));
    }

    public function create(Patient $patient)
    {
        return view('admin.medical-record-management.create', compact('patient'));
    }

    public function store(MedicalRecordRequest $request, Patient $patient)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;

        $patient->medicalRecords()->create($data);

        return redirect()->route('patients.medical-records.index', $patient)
            ->with('success', 'Catatan medis berhasil ditambahkan');
    }

    public function show(Patient $patient, MedicalRecord $medicalRecord)
    {
        return view('admin.medical-record-management.show', compact('patient', 'medicalRecord'));
    }

    public function edit(Patient $patient, MedicalRecord $medicalRecord)
    {
        return view('admin.medical-record-management.edit', compact('patient', 'medicalRecord'));
    }

    public function update(MedicalRecordRequest $request, Patient $patient, MedicalRecord $medicalRecord)
    {
        $medicalRecord->update($request->validated());

        return redirect()->route('patients.medical-records.index', $patient)
            ->with('success', 'Catatan medis berhasil diupdate');
    }

    public function destroy(Patient $patient, MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();

        return back()->with('success', 'Catatan medis berhasil dihapus');
    }

    public function export(Patient $patient)
    {
        $records = $patient->medicalRecords;
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.medical-history', compact('patient', 'records'));
        
        return $pdf->download("medical-history-{$patient->id}.pdf");
    }
}