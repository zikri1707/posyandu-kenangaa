<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Models\Patient;
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
     * Show the form for creating a new patient.
     */
    public function create(Request $request)
    {
        $this->authorize('create', Patient::class);

        if (!$request->has('category')) {
            return view('livewire.admin.patient-management.select-category');
        }

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
            ->when($request->history_search, function ($q) use ($request) {
                $q->where(function ($sq) use ($request) {
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
            'file' => 'required|file|mimes:csv,xlsx,xls|max:5120',
            'posyandu_id' => 'required|exists:posyandus,id',
        ], [
            'file.required' => 'File wajib diunggah.',
            'file.mimes' => 'Format file harus CSV, XLSX, atau XLS (Excel lama).',
            'file.max' => 'Ukuran file maksimal 5 MB.',
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
            \Log::error('Patient import failed: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'Import gagal: '.$e->getMessage());
        }
    }

    public function downloadTemplate(Request $request)
    {
        $category = $request->query('category', 'balita');

        $filename = match ($category) {
            'ibu_hamil' => 'template_import_ibu_hamil.csv',
            'lansia' => 'template_import_lansia.csv',
            default => 'template_import_balita.csv',
        };

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $rows = $this->getTemplateRowsForCategory($category);

        return response()->stream(function () use ($rows) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // BOM UTF-8
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

        $message .= '.';

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
        return $import->errors;
    }

    /**
     * Get template rows for CSV download based on category.
     */
    private function getTemplateRowsForCategory(string $category): array
    {
        if ($category === 'ibu_hamil') {
            return [
                [
                    'NIK', 'nama', 'tgl_lahir', 'jk', 'suami',
                    'tempat_lahir', 'phone_number', 'RT', 'RW', 'ALAMAT',
                    'apakah_hamil', 'TANGGAL UKUR', 'BERAT', 'TINGGI', 'LILA',
                ],
                [
                    '3275014102920002', 'SITI AMINAH', '1992-02-14', 'P', 'BUDI SANTOSO',
                    'Bekasi', '082345678901', '5', '11', 'JL. CENDRAWASIH NO. 12',
                    'Ya', '2026-03-15', '65.2', '160.0', '24.5',
                ],
                [
                    '3275014102920005', 'HANIFAH', '1995-05-20', 'P', 'AGUS WIDODO',
                    'Jakarta', '082345678902', '3', '11', 'JL. MERPATI NO. 5',
                    'Ya', '2026-03-15', '60.0', '158.0', '23.8',
                ]
            ];
        }

        if ($category === 'lansia') {
            return [
                [
                    'NIK', 'nama', 'tgl_lahir', 'jk', 'tempat_lahir',
                    'phone_number', 'RT', 'RW', 'ALAMAT', 'riwayat_penyakit',
                    'TANGGAL UKUR', 'BERAT', 'TINGGI',
                ],
                [
                    '3275010101500003', 'KARTOSUWIRYO', '1950-01-01', 'L', 'Solo',
                    '085678901234', '2', '11', 'JL. MATARAMAN NO. 45', 'Hipertensi, Asam Urat',
                    '2026-03-15', '70.0', '165.0',
                ],
                [
                    '3275014101550004', 'SUHARTINI', '1955-08-12', 'P', 'Yogyakarta',
                    '085678901235', '4', '11', 'JL. DUKUH NO. 8', 'Diabetes',
                    '2026-03-15', '55.5', '150.0',
                ]
            ];
        }

        // Default: balita
        return [
            [
                'NIK', 'nama_anak', 'tgl_lahir', 'jk', 'nm_ortu',
                'tempat_lahir', 'phone_number', 'RT', 'RW', 'ALAMAT',
                'TANGGAL UKUR', 'BERAT', 'TINGGI', 'LILA', 'lingkar_kepala',
                'CARA UKUR', 'vitamin', 'Imunisasi',
            ],
            [
                '3275010608224411', 'A. ZAFRAN. U.R', '2022-08-06', 'L', 'RYAN. R. R',
                'Jakarta', '081234567890', '4', '11', 'JL. P. NUSANTARA',
                '2026-03-15', '12.5', '85.0', '14.5', '48.0',
                'Berdiri', 'Ya', 'DPT-HB-Hib 3',
            ],
            [
                '3275015101220001', 'AISYAH HANIN.K', '2022-01-11', 'P', 'YUNIAR. P',
                'Bekasi', '081234567891', '3', '11', 'JL. P. MADURA',
                '2026-03-15', '11.0', '82.0', '13.8', '47.5',
                'Berdiri', '', 'Campak MR',
            ]
        ];
    }
}
