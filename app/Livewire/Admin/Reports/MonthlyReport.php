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
    public int $startMonth;

    public int $startYear;

    public int $endMonth;

    public int $endYear;

    public ?int $selectedPosyanduId = null;

    public bool $reportGenerated = false;

    public string $sortBy = 'visit_date_desc';

    public string $search = '';

    // Stats
    public int $totalKunjungan = 0;

    public int $balitaStunting = 0;

    public int $totalIbuHamil = 0;

    public float $cakupanVitaminA = 0;

    public function mount(): void
    {
        $now = now();
        $this->endMonth = (int) $now->month;
        $this->endYear = (int) $now->year;
        
        // Default ke 6 bulan sebelumnya
        $sixMonthsAgo = $now->subMonths(6);
        $this->startMonth = (int) $sixMonthsAgo->month;
        $this->startYear = (int) $sixMonthsAgo->year;

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

        // Get date range
        $startDate = sprintf('%04d-%02d-01', $this->startYear, $this->startMonth);
        $endDate = date('Y-m-t', strtotime(sprintf('%04d-%02d-01', $this->endYear, $this->endMonth)));

        $this->totalKunjungan = (clone $baseRecordQuery)
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->count();

        $this->balitaStunting = (clone $basePatientQuery)
            ->where('category', 'balita')
            ->whereHas('medicalRecords', function ($q) use ($startDate, $endDate) {
                $q->whereIn('nutrition_status', ['Gizi Buruk', 'Gizi Buruk/Stunting'])
                    ->whereBetween('visit_date', [$startDate, $endDate]);
            })
            ->count();

        $this->totalIbuHamil = (clone $basePatientQuery)->where('category', 'ibu_hamil')->count();

        $totalBalitaKunjungan = (clone $baseRecordQuery)
            ->whereHas('patient', fn ($q) => $q->where('category', 'balita'))
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->count();

        $vitaminADiberikan = (clone $baseRecordQuery)
            ->whereHas('patient', fn ($q) => $q->where('category', 'balita'))
            ->whereBetween('visit_date', [$startDate, $endDate])
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
            $reportData = $reportService->generateMonthlyReport($posyanduId, $this->endMonth, $this->endYear);
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
            $reportData = $reportService->generateMonthlyReport($posyanduId, $this->endMonth, $this->endYear);
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
        if ($user->isSuperAdmin()) {
            return $this->selectedPosyanduId;
        }

        return $user->posyandu_id;
    }

    public function getMonthNameProperty(int $month = null): string
    {
        $month = $month ?? $this->endMonth;
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $months[$month] ?? '';
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
            $startDate = sprintf('%04d-%02d-01', $this->startYear, $this->startMonth);
            $endDate = date('Y-m-t', strtotime(sprintf('%04d-%02d-01', $this->endYear, $this->endMonth)));
            
            $query = MedicalRecord::with(['patient', 'user'])
                ->whereHas('patient', fn ($q) => $q->where('posyandu_id', $posyanduId))
                ->whereBetween('visit_date', [$startDate, $endDate]);

            // Apply search filter
            if ($this->search) {
                $query->whereHas('patient', fn ($q) => 
                    $q->where('full_name', 'like', '%' . $this->search . '%')
                        ->orWhere('id_number', 'like', '%' . $this->search . '%')
                );
            }

            // Apply sorting
            $query = match($this->sortBy) {
                'patient_name_asc' => $query->join('patients', 'medical_records.patient_id', '=', 'patients.id')->orderBy('patients.full_name', 'asc')->select('medical_records.*'),
                'patient_name_desc' => $query->join('patients', 'medical_records.patient_id', '=', 'patients.id')->orderBy('patients.full_name', 'desc')->select('medical_records.*'),
                'visit_date_asc' => $query->orderBy('visit_date', 'asc'),
                'visit_date_desc' => $query->orderBy('visit_date', 'desc'),
                'updated_at_asc' => $query->orderBy('updated_at', 'asc'),
                'updated_at_desc' => $query->orderBy('updated_at', 'desc'),
                default => $query->latest('visit_date'),
            };

            $total = $query->count();
            $records = $query->paginate(10);
        }

        $periodLabel = $this->getMonthNameProperty($this->startMonth) . ' ' . $this->startYear;
        if ($this->startMonth !== $this->endMonth || $this->startYear !== $this->endYear) {
            $periodLabel .= ' - ' . $this->getMonthNameProperty($this->endMonth) . ' ' . $this->endYear;
        }

        return view('livewire.admin.reports.monthly-report', [
            'records' => $records,
            'total' => $total,
            'posyandus' => $this->getAllowedPosyandus(),
            'periodLabel' => $periodLabel,
            'posyanduName' => $this->getPosyanduNameProperty(),
        ]);
    }
}
