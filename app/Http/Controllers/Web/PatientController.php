<?php

namespace App\Http\Controllers\Web;

use App\Models\Patient;
use App\Http\Requests\PatientRequest;
use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Services\PatientService;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __construct(
        private PatientService $patientService,
        private ActivityLogService $activityLogService
    ) {}

    /**
     * Display a listing of patients.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Patient::class);

        $patients = Patient::with('posyandu')
            ->accessibleBy(auth()->user())
            ->when($request->search, fn($q) => $q->where(function($q2) use ($request) {
                $q2->where('full_name', 'like', '%'.$request->search.'%')
                   ->orWhere('id_number', 'like', '%'.$request->search.'%');
            }))
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.patient-management.index', compact('patients'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        $this->authorize('create', Patient::class);

        $pedukuhans = \App\Models\Pedukuhan::all();
        $posyandus = $this->getAvailablePosyandus();

        return view('livewire.admin.patient-management.create', compact('pedukuhans', 'posyandus'));
    }

    /**
     * Store a newly created patient.
     */
    public function store(PatientRequest $request)
    {
        $this->authorize('create', Patient::class);

        try {
            $this->patientService->createPatient($request->validated(), auth()->user());
            return redirect()->route('admin.patients.index')->with('success', 'Data warga berhasil disimpan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        }
    }

    /**
     * Display the specified patient with medical records history.
     */
    public function show(Patient $patient, Request $request)
    {
        $this->authorize('view', $patient);

        $patient->load(['posyandu']);

        $medicalRecords = $patient->medicalRecords()
            ->with('user')
            ->when($request->history_search, function($q) use ($request) {
                $q->where(function($sq) use ($request) {
                    $sq->where('diagnosis', 'like', '%'.$request->history_search.'%')
                       ->orWhere('immunization', 'like', '%'.$request->history_search.'%')
                       ->orWhere('visit_date', 'like', '%'.$request->history_search.'%');
                });
            })
            ->latest('visit_date')
            ->paginate(10)
            ->withQueryString();

        return view('livewire.admin.patient-management.details', compact('patient', 'medicalRecords'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Patient $patient)
    {
        $this->authorize('update', $patient);

        $pedukuhans = \App\Models\Pedukuhan::all();
        $posyandus = $this->getAvailablePosyandus();

        return view('livewire.admin.patient-management.update', compact('patient', 'pedukuhans', 'posyandus'));
    }

    /**
     * Update the specified patient.
     */
    public function update(PatientRequest $request, Patient $patient)
    {
        $this->authorize('update', $patient);

        try {
            $this->patientService->updatePatient($patient, $request->validated(), auth()->user());
            return redirect()->route('admin.patients.index')->with('success', 'Data warga berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        }
    }

    /**
     * Remove the specified patient.
     */
    public function destroy(Patient $patient)
    {
        $this->authorize('delete', $patient);

        $this->patientService->deletePatient($patient);

        return redirect()->route('admin.patients.index')->with('success', 'Data warga berhasil dihapus.');
    }

    /**
     * Show import form.
     */
    public function importForm()
    {
        $this->authorize('create', Patient::class);

        $posyandus = $this->getAvailablePosyandus();

        return view('livewire.admin.patient-management.import', compact('posyandus'));
    }

    /**
     * Process CSV/Excel import.
     */
    public function import(Request $request)
    {
        $this->authorize('create', Patient::class);

        $request->validate([
            'file'        => 'required|file|mimes:csv,xlsx|max:5120',
            'posyandu_id' => 'required|exists:posyandus,id',
        ], [
            'file.required'        => 'File wajib diunggah.',
            'file.mimes'           => 'Format file harus CSV atau XLSX. File .xls (Excel lama) tidak didukung — simpan ulang sebagai .xlsx atau .csv terlebih dahulu.',
            'file.max'             => 'Ukuran file maksimal 5 MB.',
            'posyandu_id.required' => 'Posyandu wajib dipilih.',
        ]);

        $user = auth()->user();
        $posyanduId = $user->isSuperAdmin() ? (int) $request->posyandu_id : $user->posyandu_id;

        try {
            $import = new \App\Imports\PatientImport($posyanduId, $user->id);
            $import->import($request->file('file'));

            $this->logImportActivity($import, $posyanduId);

            return redirect()->route('admin.patients.index')
                ->with('success', $this->buildImportSuccessMessage($import))
                ->with('import_errors', $this->buildImportErrors($import));

        } catch (\Exception $e) {
            \Log::error('Patient import failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    /**
     * Download import template.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_warga_posyandu.csv"',
        ];

        $rows = $this->getTemplateRows();

        return response()->stream(function () use ($rows) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM UTF-8
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, 200, $headers);
    }

    /**
     * Get available posyandus based on user role.
     */
    private function getAvailablePosyandus()
    {
        $user = auth()->user();

        return $user->isSuperAdmin()
            ? \App\Models\Posyandu::orderBy('name')->get()
            : \App\Models\Posyandu::where('id', $user->posyandu_id)->get();
    }

    /**
     * Log import activity.
     */
    private function logImportActivity($import, int $posyanduId): void
    {
        $posyandu = \App\Models\Posyandu::find($posyanduId);
        
        $this->activityLogService->log(
            'create_patient',
            "Import data warga: {$import->imported} berhasil, {$import->skipped} dilewati — Posyandu {$posyandu?->name}",
            $posyanduId,
            'Patient'
        );
    }

    /**
     * Build success message for import.
     */
    private function buildImportSuccessMessage($import): string
    {
        $message = "Import selesai: {$import->imported} warga berhasil diimpor";
        
        if ($import->recordsImported > 0) {
            $message .= ", {$import->recordsImported} rekam medis tersimpan";
        }
        
        $message .= ".";
        
        if ($import->skipped > 0) {
            $message .= " {$import->skipped} baris dilewati.";
        }

        return $message;
    }

    /**
     * Build errors array for import.
     */
    private function buildImportErrors($import): array
    {
        $errors = $import->errors;

        if (!empty($import->debugHeaders)) {
            $errors = array_merge(
                ['[DEBUG] Header terdeteksi: ' . implode(' | ', $import->debugHeaders)],
                $errors
            );
        }

        return $errors;
    }

    /**
     * Get template rows for CSV download.
     */
    private function getTemplateRows(): array
    {
        return [
            [
                'NIK', 'nama_anak', 'tgl_lahir', 'jk',
                'nm_ortu', 'RT', 'RW', 'ALAMAT',
                'TANGGAL UKUR', 'BERAT', 'TINGGI', 'LILA', 'lingkar_kepala',
                'CARA UKUR', 'vitamin', 'asi_bulan_0', 'Imunisasi',
            ],
            [
                '3275010608224411', 'A. ZAFRAN. U.R', '2022-08-06', 'L',
                'RYAN. R. R', '4', '11', 'JL. P. NUSANTARA',
                '2026-03-15', '12.5', '85.0', '14.5', '48.0',
                'Berdiri', 'Ya', '', 'DPT-HB-Hib 3',
            ],
            [
                '', 'AISYAH HANIN.K', '2022-01-11', 'P',
                'YUNIAR. P', '3', '11', 'JL. P. MADURA',
                '2026-03-15', '11.0', '82.0', '13.8', '47.5',
                'Berdiri', '', '', 'Campak MR',
            ],
        ];
    }
}
