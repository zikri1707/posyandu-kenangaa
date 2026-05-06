<?php

namespace App\Livewire\Admin\Reports;

use App\Livewire\Shared\BaseAdminComponent;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Posyandu;
use App\Services\ActivityLogService;
use App\Services\ReportService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MonthlyReport extends BaseAdminComponent
{
    public int $selectedMonth;

    public int $selectedYear;

    public ?int $selectedPosyanduId = null;

    public bool $reportGenerated = false;

    // Stats
    public int $totalKunjungan = 0;

    public int $balitaStunting = 0;

    public int $totalIbuHamil = 0;

    public float $cakupanVitaminA = 0;

    public function mount(): void
    {
        $this->selectedMonth = (int) now()->month;
        $this->selectedYear = (int) now()->year;

        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            $this->selectedPosyanduId = Posyandu::first()?->id;
        } else {
            $this->selectedPosyanduId = $user->posyandu_id;
        }
    }

    public function generateReport(): void
    {
        $this->resetPage();
        $this->reportGenerated = true;
        $this->loadStats();
    }

    protected function loadStats(): void
    {
        $posyanduId = $this->getEffectivePosyanduId();
        if (! $posyanduId) {
            return;
        }

        // Gunakan applyPosyanduScope (disesuaikan untuk spesifik Posyandu ID yang dipilih)
        $basePatientQuery = Patient::where('posyandu_id', $posyanduId);
        $baseRecordQuery = MedicalRecord::whereHas('patient', fn ($q) => $q->where('posyandu_id', $posyanduId));

        $this->totalKunjungan = (clone $baseRecordQuery)
            ->whereMonth('visit_date', $this->selectedMonth)
            ->whereYear('visit_date', $this->selectedYear)
            ->count();

        $this->balitaStunting = (clone $basePatientQuery)
            ->where('category', 'balita')
            ->whereHas('medicalRecords', function ($q) {
                $q->whereIn('nutrition_status', ['Gizi Buruk', 'Gizi Buruk/Stunting'])
                    ->whereMonth('visit_date', $this->selectedMonth)
                    ->whereYear('visit_date', $this->selectedYear);
            })
            ->count();

        $this->totalIbuHamil = (clone $basePatientQuery)->where('category', 'ibu_hamil')->count();

        $totalBalitaKunjungan = (clone $baseRecordQuery)
            ->whereHas('patient', fn ($q) => $q->where('category', 'balita'))
            ->whereMonth('visit_date', $this->selectedMonth)
            ->whereYear('visit_date', $this->selectedYear)
            ->count();

        $vitaminADiberikan = (clone $baseRecordQuery)
            ->whereHas('patient', fn ($q) => $q->where('category', 'balita'))
            ->whereMonth('visit_date', $this->selectedMonth)
            ->whereYear('visit_date', $this->selectedYear)
            ->where('vitamin_a', true)
            ->count();

        $this->cakupanVitaminA = $totalBalitaKunjungan > 0
            ? round(($vitaminADiberikan / $totalBalitaKunjungan) * 100, 1)
            : 0;
    }

    public function exportExcel(ReportService $reportService, ActivityLogService $activityLogService): void
    {
        $posyanduId = $this->getEffectivePosyanduId();
        if (! $posyanduId) {
            return;
        }

        try {
            $posyandu = Posyandu::findOrFail($posyanduId);
            $reportData = $reportService->generateMonthlyReport($posyanduId, $this->selectedMonth, $this->selectedYear);
            $filePath = $reportService->exportToExcel($reportData, $posyandu->name);

            $activityLogService->log(
                'export_report',
                "Ekspor laporan Excel: {$posyandu->name} - {$reportData['period']['month_name']} {$reportData['period']['year']}",
                $posyanduId,
                'Posyandu'
            );

            $this->dispatch('download-file', url: asset('storage/exports/'.basename($filePath)));
            $this->notify('Ekspor berhasil. Berkas sedang diunduh.');
        } catch (\Exception $e) {
            Log::error('Export Excel failed: '.$e->getMessage());
            $this->notify('Ekspor gagal. Silakan coba lagi.', 'error');
        }
    }

    public function exportPdf(ReportService $reportService, ActivityLogService $activityLogService)
    {
        $posyanduId = $this->getEffectivePosyanduId();
        if (! $posyanduId) {
            return;
        }

        try {
            $posyandu = Posyandu::findOrFail($posyanduId);
            $reportData = $reportService->generateMonthlyReport($posyanduId, $this->selectedMonth, $this->selectedYear);
            $filePath = $reportService->exportToPdf($reportData, $posyandu->name);

            $activityLogService->log(
                'export_report',
                "Ekspor laporan PDF: {$posyandu->name} - {$reportData['period']['month_name']} {$reportData['period']['year']}",
                $posyanduId,
                'Posyandu'
            );

            return response()->download($filePath);
        } catch (\Exception $e) {
            Log::error('Export PDF failed: '.$e->getMessage());
            $this->notify('Ekspor gagal. Silakan coba lagi.', 'error');
        }
    }

    protected function getEffectivePosyanduId(): ?int
    {
        $user = Auth::user();
        if ($user->isSuperAdmin() || $user->isCoordinator()) {
            return $this->selectedPosyanduId;
        }

        return $user->posyandu_id;
    }

    public function getMonthNameProperty(): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $months[$this->selectedMonth] ?? '';
    }

    public function getPosyanduNameProperty(): string
    {
        $id = $this->getEffectivePosyanduId();

        return $id ? (Posyandu::find($id)?->name ?? '-') : '-';
    }

    public function render()
    {
        $posyanduId = $this->getEffectivePosyanduId();
        $records = collect();
        $total = 0;

        if ($this->reportGenerated && $posyanduId) {
            $query = MedicalRecord::with(['patient', 'user'])
                ->whereHas('patient', fn ($q) => $q->where('posyandu_id', $posyanduId))
                ->whereMonth('visit_date', $this->selectedMonth)
                ->whereYear('visit_date', $this->selectedYear)
                ->latest('visit_date');

            $total = $query->count();
            $records = $query->paginate(10);
        }

        return view('livewire.admin.reports.monthly-report', [
            'records' => $records,
            'total' => $total,
            'posyandus' => $this->getAllowedPosyandus(),
            'monthName' => $this->getMonthNameProperty(),
            'posyanduName' => $this->getPosyanduNameProperty(),
        ]);
    }
}
