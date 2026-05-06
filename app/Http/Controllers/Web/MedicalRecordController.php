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

        $patients = $this->getAvailablePatients();
        $duplicateWarnings = $this->checkDuplicateWarnings(
            request()->get('patient_id'),
            null,
            null
        );

        return view('livewire.admin.medical-record-management.create', compact('patients', 'duplicateWarnings'));
    }

    /**
     * Simpan rekam medis baru
     */
    public function store(MedicalRecordRequest $request): RedirectResponse
    {
        $this->authorize('create', MedicalRecord::class);

        $this->medicalRecordService->createRecord($request->validated(), auth()->user());

        return redirect()
            ->route('admin.medical-records.index')
            ->with('success', 'Rekam medis berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail rekam medis
     */
    public function show(MedicalRecord $medicalRecord): View
    {
        $this->authorize('view', $medicalRecord);

        return view('livewire.admin.medical-record-management.details', compact('medicalRecord'));
    }

    /**
     * Tampilkan form edit rekam medis
     */
    public function edit(MedicalRecord $medicalRecord): View
    {
        $this->authorize('update', $medicalRecord);

        $patients = $this->getAvailablePatients();
        $duplicateWarnings = $this->checkDuplicateWarnings(
            $medicalRecord->patient_id,
            $medicalRecord->visit_date,
            $medicalRecord->id
        );

        return view('livewire.admin.medical-record-management.update', compact('medicalRecord', 'patients', 'duplicateWarnings'));
    }

    /**
     * Update rekam medis yang sudah ada
     */
    public function update(MedicalRecordRequest $request, MedicalRecord $medicalRecord): RedirectResponse
    {
        $this->authorize('update', $medicalRecord);

        $this->medicalRecordService->updateRecord($medicalRecord, $request->validated(), auth()->user());

        return redirect()
            ->route('admin.medical-records.index')
            ->with('success', 'Rekam medis berhasil diperbarui.');
    }

    /**
     * Hapus rekam medis
     */
    public function destroy(MedicalRecord $medicalRecord): RedirectResponse
    {
        $this->authorize('delete', $medicalRecord);

        $this->medicalRecordService->deleteRecord($medicalRecord);

        return redirect()
            ->route('admin.medical-records.index')
            ->with('success', 'Rekam medis berhasil dihapus.');
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
            return Patient::all();
        }

        // Admin, Kader, dan Staff hanya bisa akses pasien di posyandu mereka
        return Patient::where('posyandu_id', $user->posyandu_id)->get();
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
