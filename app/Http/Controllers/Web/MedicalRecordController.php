<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalRecordRequest;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Services\MedicalRecordService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Controller untuk mengelola rekam medis pasien
 *
 * Menerapkan prinsip:
 * - Single Responsibility Principle
 * - Dependency Injection
 * - DRY (Don't Repeat Yourself)
 */
class MedicalRecordController extends Controller
{
    private MedicalRecordService $medicalRecordService;

    /**
     * Constructor dengan dependency injection
     */
    public function __construct(MedicalRecordService $medicalRecordService)
    {
        $this->medicalRecordService = $medicalRecordService;
    }

    /**
     * Tampilkan daftar rekam medis
     */
    public function index(): View
    {
        $this->authorize('viewAny', MedicalRecord::class);

        $medicalRecords = MedicalRecord::with(['patient', 'user'])
            ->accessibleBy(auth()->user())
            ->latest('visit_date')
            ->paginate(10);

        return view('livewire.admin.medical-record-management.index', compact('medicalRecords'));
    }

    /**
     * Tampilkan form pembuatan rekam medis baru
     */
    public function create(): View
    {
        $this->authorize('create', MedicalRecord::class);

        if (! request()->has('category')) {
            return view('livewire.admin.medical-record-management.select-category');
        }

        $patients = $this->getAvailablePatients();

        $category = request('category');
        if ($category === 'balita') {
            $patients = $patients->filter(fn ($p) => in_array($p->category, ['bayi', 'baduta', 'balita', 'anak_sekolah']));
        } elseif ($category === 'ibu_hamil') {
            $patients = $patients->filter(fn ($p) => $p->category === 'ibu_hamil');
        } elseif ($category === 'lansia') {
            $patients = $patients->filter(fn ($p) => $p->category === 'lansia');
        }

        $selectedPatient = request()->has('patient_id') ? Patient::find(request('patient_id')) : null;

        $duplicateWarnings = $this->checkDuplicateWarnings(
            request()->get('patient_id'),
            null,
            null
        );

        return view('livewire.admin.medical-record-management.create', compact('patients', 'duplicateWarnings', 'selectedPatient'));
    }

    /**
     * Simpan rekam medis baru
     */
    public function store(MedicalRecordRequest $request): RedirectResponse
    {
        $this->authorize('create', MedicalRecord::class);

        try {
            $this->medicalRecordService->createRecord($request->validated(), auth()->user());

            return redirect()
                ->route('admin.medical-records.index')
                ->with('success', 'Rekam medis berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal menyimpan rekam medis: '.$e->getMessage(), [
                'user_id' => auth()->id(),
                'patient_id' => $request->patient_id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan rekam medis: '.$e->getMessage());
        }
    }

    /**
     * Tampilkan detail rekam medis
     */
    public function show(MedicalRecord $medicalRecord): View
    {
        $this->authorize('view', $medicalRecord);

        $medicalRecord->load(['patient', 'user', 'childDevelopment']);

        return view('livewire.admin.medical-record-management.details', compact('medicalRecord'));
    }

    /**
     * Tampilkan form edit rekam medis
     */
    public function edit(MedicalRecord $medicalRecord): View
    {
        $this->authorize('update', $medicalRecord);

        $medicalRecord->load('childDevelopment');

        $patients = $this->getAvailablePatients();
        $duplicateWarnings = $this->checkDuplicateWarnings(
            $medicalRecord->patient_id,
            $medicalRecord->visit_date,
            $medicalRecord->id
        );

        return view('livewire.admin.medical-record-management.update', [
            'record' => $medicalRecord,
            'patients' => $patients,
            'duplicateWarnings' => $duplicateWarnings,
        ]);
    }

    /**
     * Update rekam medis yang sudah ada
     */
    public function update(MedicalRecordRequest $request, MedicalRecord $medicalRecord): RedirectResponse
    {
        $this->authorize('update', $medicalRecord);

        try {
            $this->medicalRecordService->updateRecord($medicalRecord, $request->validated(), auth()->user());

            return redirect()
                ->route('admin.medical-records.index')
                ->with('success', 'Rekam medis berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal memperbarui rekam medis: '.$e->getMessage(), [
                'user_id' => auth()->id(),
                'record_id' => $medicalRecord->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui rekam medis. Silakan coba lagi.');
        }
    }

    /**
     * Hapus rekam medis
     */
    public function destroy(MedicalRecord $medicalRecord): RedirectResponse
    {
        $this->authorize('delete', $medicalRecord);

        try {
            $this->medicalRecordService->deleteRecord($medicalRecord);

            return redirect()
                ->route('admin.medical-records.index')
                ->with('success', 'Rekam medis berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal menghapus rekam medis: '.$e->getMessage(), [
                'user_id' => auth()->id(),
                'record_id' => $medicalRecord->id,
            ]);

            return redirect()
                ->route('admin.medical-records.index')
                ->with('error', 'Gagal menghapus rekam medis. Silakan coba lagi.');
        }
    }

    /**
     * Dapatkan daftar pasien yang tersedia berdasarkan role user
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAvailablePatients()
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return Patient::with(['medicalRecords'])->orderBy('full_name', 'asc')->get();
        }

        // Admin, Kader, dan Staff hanya bisa akses pasien di posyandu mereka
        return Patient::where('posyandu_id', $user->posyandu_id)
            ->orderBy('full_name', 'asc')
            ->with(['medicalRecords' => function ($q) {
                $q->orderBy('visit_date', 'desc')->limit(2);
            }])
            ->get();
    }

    /**
     * Periksa peringatan duplikasi Vitamin A dan Pill FE
     *
     * @param  \Illuminate\Support\Carbon|null  $visitDate
     * @return mixed
     */
    private function checkDuplicateWarnings(
        ?int $patientId = null,
        $visitDate = null,
        ?int $excludeRecordId = null
    ) {
        if (! $patientId) {
            return null;
        }

        return $this->medicalRecordService->getDuplicateWarnings(
            $patientId,
            $visitDate,
            $excludeRecordId
        );
    }
}
