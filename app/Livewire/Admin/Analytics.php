<?php

namespace App\Livewire\Admin;

use App\Jobs\ComputeAnalyticsSnapshot;
use App\Livewire\Shared\BaseAdminComponent;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Posyandu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Analytics extends BaseAdminComponent
{
    public int $selectedYear;

    public array $years = [];

    public ?string $lastUpdated = null;

    public string $activeTab = 'overview';

    // Overview stats — total terdaftar (semua, tanpa filter tahun)
    public int $totalBalita = 0;

    public int $totalIbuHamil = 0;

    public int $totalLansia = 0;

    // Jumlah yang aktif berkunjung di tahun dipilih
    public int $balitaBerkunjung = 0;

    public int $ibuHamilBerkunjung = 0;

    public int $lansiaBerkunjung = 0;

    public int $totalKunjungan = 0;

    public float $stuntingRate = 0;

    public float $cakupanImunisasi = 0;

    public int $kaderAktif = 0;

    // Trend data (12 bulan)
    public array $trendLabels = [];

    public array $trendVisitsBalita = [];

    public array $trendVisitsIbuHamil = [];

    public array $trendVisitsLansia = [];

    // Additional Trend Arrays for Compare/Yearly Modes
    public array $trendCompareCurrent = [];

    public array $trendComparePrevious = [];

    public array $trendLabelsPrevious = [];

    public array $trendNormal = [];

    public array $trendStunting = [];

    public array $trendRisk = [];

    // Nutrition distribution
    public array $nutritionLabels = [];

    public array $nutritionData = [];

    // Stunting by posyandu
    public array $stuntingByPosyandu = [];

    // Demographics
    public int $usia0_12 = 0;

    public int $usia12_24 = 0;

    public int $usia24plus = 0;

    // Recent records
    public ?object $recentRecords = null;

    public ?object $recentPregnancyRecords = null;

    public ?object $recentLansiaRecords = null;

    // Vaccine distribution
    public array $vaccineLabels = [];

    public array $vaccineData = [];

    // Ibu Hamil Tab
    public float $hypertensionRiskRate = 0;

    public float $feComplianceRate = 0;

    public array $trendPregnancyHypertension = [];

    public array $trendPregnancyFe = [];

    // Lansia Tab
    public float $lansiaHypertensionRate = 0;

    public float $lansiaHyperglycemiaRate = 0;

    public float $lansiaHypercholesterolemiaRate = 0;

    public float $lansiaHyperuricemiaRate = 0;

    public array $trendLansiaHypertension = [];

    public array $trendLansiaHyperglycemia = [];

    public array $trendLansiaHypercholesterolemia = [];

    public array $trendLansiaHyperuricemia = [];

    // Balita Growth Trend
    public array $trendAvgWeight = [];

    public array $trendAvgHeight = [];

    public ?int $selectedMonth = null; // null means full year

    public ?int $selectedPosyandu = null;

    public string $viewMode = 'monthly'; // 'monthly', 'yearly'

    public bool $compareMode = false;

    // ── ANA-15: Search on recent records tables ──
    public string $tableSearch = '';

    // ── ANA-16: Gender filter ──
    public string $filterGender = ''; // '' = all, 'L' = male, 'P' = female

    // ── ANA-22: Drill-down from chart click ──
    public bool $showDrillDown = false;

    public string $drillDownTitle = '';

    public array $drillDownData = [];

    public function mount(): void
    {
        $this->selectedYear = (int) now()->year;
        $this->years = range($this->selectedYear, max(2020, $this->selectedYear - 4));
        $this->loadData();
    }

    public function updatedSelectedYear(): void
    {
        $this->loadData();
    }

    public function updatedSelectedMonth(): void
    {
        $this->loadData();
    }

    public function updatedSelectedPosyandu(): void
    {
        $this->loadData();
    }

    public function updatedViewMode(): void
    {
        $this->loadData();
    }

    public function updatedCompareMode(): void
    {
        $this->loadData();
    }

    public function resetFilters(): void
    {
        $this->selectedMonth = null;
        $this->selectedPosyandu = null;
        $this->viewMode = 'monthly';
        $this->compareMode = false;
        $this->loadData();
    }

    public function updatedActiveTab(): void
    {
        $this->tableSearch = '';
        $this->drillDownData = [];
        $this->showDrillDown = false;
        $this->loadData();
    }

    // ── ANA-15: Search filter updates ──
    public function updatedTableSearch(): void
    {
        // Triggers re-render — filtering happens in blade via @php
    }

    // ── ANA-16: Gender filter ──
    public function updatedFilterGender(): void
    {
        $this->loadData();
    }

    // ── ANA-22: Chart click drill-down ──
    public function drillDown(string $label, string $type, ?int $month = null): void
    {
        $this->showDrillDown = true;
        $this->drillDownTitle = "Detail: {$label}";

        $query = $this->applyPosyanduScope(MedicalRecord::query(), $this->selectedPosyandu)
            ->with(['patient.posyandu'])
            ->whereYear('visit_date', $this->selectedYear);

        if ($month) {
            $query->whereMonth('visit_date', $month);
        } elseif ($this->selectedMonth) {
            $query->whereMonth('visit_date', $this->selectedMonth);
        }

        match ($type) {
            'stunting' => $query->whereHas('patient', fn ($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))
                ->where(fn ($q) => $q->where('nutrition_status', 'like', '%Stunting%')
                    ->orWhere('nutrition_status', 'like', '%Pendek%')
                    ->orWhere('stunting_status', '!=', 'Normal')),
            'gizi_buruk' => $query->whereHas('patient', fn ($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))
                ->where(fn ($q) => $q->where('nutrition_status', 'like', '%Buruk%')
                    ->orWhere('wasting_status', 'Gizi Buruk')),
            'balita' => $query->whereHas('patient', fn ($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta'])),
            'ibu_hamil' => $query->whereHas('patient', fn ($q) => $q->where('category', 'ibu_hamil')),
            'lansia' => $query->whereHas('patient', fn ($q) => $q->where('category', 'lansia')),
            default => null,
        };

        $this->drillDownData = $query->latest('visit_date')->limit(50)->get()->map(fn ($r) => [
            'name' => $r->patient?->full_name ?? '-',
            'nik' => $r->patient?->id_number ?? '-',
            'posyandu' => $r->patient?->posyandu?->name ?? '-',
            'nutrition_status' => $r->nutrition_status ?? '-',
            'visit_date' => $r->visit_date?->format('d M Y') ?? '-',
            'patient_id' => $r->patient_id,
        ])->toArray();
    }

    public function closeDrillDown(): void
    {
        $this->showDrillDown = false;
        $this->drillDownData = [];
        $this->drillDownTitle = '';
    }

    /**
     * Refresh data manually (clears cache)
     */
    public function refreshStats(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $posyanduId = $user->isSuperAdmin() ? null : $user->posyandu_id;

        // Dispatch job for all relevant keys (current year, and optionally current month)
        ComputeAnalyticsSnapshot::dispatch($posyanduId, $this->selectedYear, $this->selectedMonth);

        $this->loadData();
    }

    protected function loadData(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $posyanduId = $user->isSuperAdmin() ? null : $user->posyandu_id;
        $key = "year_{$this->selectedYear}".($this->selectedMonth ? "_month_{$this->selectedMonth}" : '');

        // Bypass snapshot if custom filters are active (DASH-09, DASH-10, DASH-11, DASH-12, DASH-31, DASH-32)
        $isCustomFilterActive = $this->selectedMonth || $this->selectedPosyandu || $this->compareMode || $this->viewMode === 'yearly';

        if (! $isCustomFilterActive) {
            $snapshot = \App\Models\AnalyticsSnapshot::where('posyandu_id', $posyanduId)
                ->where('key', $key)
                ->first();

            if ($snapshot) {
                $data = $snapshot->data['analytics_data'];
                $this->lastUpdated = $snapshot->last_computed_at->format('d M Y H:i');

                // Auto-refresh in background if data is older than 1 hour
                if ($snapshot->last_computed_at->diffInHours(now()) >= 1) {
                    ComputeAnalyticsSnapshot::dispatch($posyanduId, $this->selectedYear, $this->selectedMonth);
                }
            } else {
                // Fallback to legacy computation if no snapshot exists
                $data = $this->fetchAnalyticsData();
                $this->lastUpdated = 'Live';


                // Dispatch job to create snapshot for next time
                ComputeAnalyticsSnapshot::dispatch($posyanduId, $this->selectedYear, $this->selectedMonth);
            }
        } else {
            // Live calculation for custom filters
            $data = $this->fetchAnalyticsData();
            $this->lastUpdated = 'Live (Custom Filter)';
        }

        foreach ($data as $k => $value) {
            if (property_exists($this, $k)) {
                $this->{$k} = $value;
            }
        }

        // Dispatch event to update charts in frontend
        $this->dispatch('charts-updated',
            trendLabels: $this->trendLabels,

            // Overview Trend
            trendVisitsBalita: $this->trendVisitsBalita,
            trendVisitsIbuHamil: $this->trendVisitsIbuHamil,
            trendVisitsLansia: $this->trendVisitsLansia,

            // Balita Charts
            trendNormal: $this->trendNormal,
            trendStunting: $this->trendStunting,
            trendRisk: $this->trendRisk,
            nutritionLabels: $this->nutritionLabels,
            nutritionData: $this->nutritionData,
            vaccineLabels: $this->vaccineLabels,
            vaccineData: $this->vaccineData,

            // Ibu Hamil Charts
            trendPregnancyHypertension: $this->trendPregnancyHypertension,
            trendPregnancyFe: $this->trendPregnancyFe,

            // Additional Chart Data
            trendCompareCurrent: $this->trendCompareCurrent,
            trendComparePrevious: $this->trendComparePrevious,
            trendLabelsPrevious: $this->trendLabelsPrevious,
            viewMode: $this->viewMode,
            compareMode: $this->compareMode,

            // Balita Growth
            trendAvgWeight: $this->trendAvgWeight,
            trendAvgHeight: $this->trendAvgHeight,

            // Lansia Charts
            trendLansiaHypertension: $this->trendLansiaHypertension,
            trendLansiaHyperglycemia: $this->trendLansiaHyperglycemia,
            trendLansiaHypercholesterolemia: $this->trendLansiaHypercholesterolemia,
            trendLansiaHyperuricemia: $this->trendLansiaHyperuricemia
        );

        // Recent records (filtered by month/year/posyandu + gender)
        $medicalRecordQuery = $this->applyPosyanduScope(MedicalRecord::query(), $this->selectedPosyandu)
            ->whereYear('visit_date', $this->selectedYear);

        if ($this->selectedMonth) {
            $medicalRecordQuery->whereMonth('visit_date', $this->selectedMonth);
        }

        // ANA-16: apply gender filter when set
        if ($this->filterGender) {
            $medicalRecordQuery->whereHas('patient', fn ($q) => $q->where('gender', $this->filterGender));
        }

        // ANA-15: apply search when set
        if ($this->tableSearch) {
            $searchTerm = '%'.$this->tableSearch.'%';
            $medicalRecordQuery->whereHas('patient', function ($q) use ($searchTerm) {
                $q->where('full_name', 'like', $searchTerm)
                    ->orWhere('id_number', 'like', $searchTerm);
            });
        }

        $this->recentRecords = (clone $medicalRecordQuery)
            ->with(['patient.posyandu'])
            ->whereHas('patient', fn ($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))
            ->latest('visit_date')
            ->limit(20)
            ->get();

        $this->recentPregnancyRecords = (clone $medicalRecordQuery)
            ->with(['patient.posyandu'])
            ->whereHas('patient', fn ($q) => $q->where('category', 'ibu_hamil'))
            ->latest('visit_date')
            ->limit(20)
            ->get();

        $this->recentLansiaRecords = (clone $medicalRecordQuery)
            ->with(['patient.posyandu'])
            ->whereHas('patient', fn ($q) => $q->where('category', 'lansia'))
            ->latest('visit_date')
            ->limit(5)
            ->get();
    }

    protected function fetchAnalyticsData(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $patientQuery = $this->applyPosyanduScope(Patient::query(), $this->selectedPosyandu);
        $medicalRecordQuery = $this->applyPosyanduScope(MedicalRecord::query(), $this->selectedPosyandu);

        $selectedYear = $this->selectedYear;
        $selectedMonth = $this->selectedMonth;

        // Determination date for age calculation
        $determinationDate = $selectedMonth
            ? Carbon::create($selectedYear, $selectedMonth)->endOfMonth()
            : Carbon::create($selectedYear)->endOfYear();

        $basePatientFilter = fn ($q) => $q->whereYear('visit_date', $selectedYear)
            ->when($selectedMonth, fn ($mq) => $mq->whereMonth('visit_date', $selectedMonth));

        // ── 1. GLOBAL OVERVIEW STATS ────────────────────────────────
        // Total terdaftar (tidak difilter tahun — semua sasaran posyandu)
        $totalBalita = $this->applyPosyanduScope(Patient::query(), $this->selectedPosyandu)
            ->whereIn('category', ['balita', 'bayi', 'baduta'])
            ->count();

        $totalIbuHamil = $this->applyPosyanduScope(Patient::query(), $this->selectedPosyandu)
            ->where('category', 'ibu_hamil')
            ->count();

        $totalLansia = $this->applyPosyanduScope(Patient::query(), $this->selectedPosyandu)
            ->where('category', 'lansia')
            ->count();

        // Yang sudah berkunjung di tahun/bulan yang dipilih
        $balitaBerkunjung = (clone $patientQuery)
            ->whereIn('category', ['balita', 'bayi', 'baduta'])
            ->whereHas('medicalRecords', $basePatientFilter)
            ->count();

        $ibuHamilBerkunjung = (clone $patientQuery)
            ->where('category', 'ibu_hamil')
            ->whereHas('medicalRecords', $basePatientFilter)
            ->count();

        $lansiaBerkunjung = (clone $patientQuery)
            ->where('category', 'lansia')
            ->whereHas('medicalRecords', $basePatientFilter)
            ->count();

        $totalKunjungan = (clone $medicalRecordQuery)
            ->whereYear('visit_date', $selectedYear)
            ->when($selectedMonth, fn ($q) => $q->whereMonth('visit_date', $selectedMonth))
            ->count();

        $kaderAktif = User::where('is_active', true)
            ->whereIn('role', ['staff', 'medical', 'admin', 'kader'])
            ->when(! $user->isSuperAdmin() && $user->posyandu_id, fn ($q) => $q->where('posyandu_id', $user->posyandu_id))
            ->count();

        // Combined Monthly Visits Trend (12 Months)
        $recordsYear = (clone $medicalRecordQuery)
            ->with('patient')
            ->whereYear('visit_date', $selectedYear)
            ->get();

        $trendLabels = [];
        $trendVisitsBalita = [];
        $trendVisitsIbuHamil = [];
        $trendVisitsLansia = [];

        for ($m = 1; $m <= 12; $m++) {
            $trendLabels[] = Carbon::create($selectedYear, $m)->translatedFormat('M');
            $monthRecords = $recordsYear->filter(fn ($r) => Carbon::parse($r->visit_date)->month === $m);
            $trendVisitsBalita[] = $monthRecords->filter(fn ($r) => $r->patient && in_array($r->patient->category, ['balita', 'bayi', 'baduta']))->count();
            $trendVisitsIbuHamil[] = $monthRecords->filter(fn ($r) => $r->patient && $r->patient->category === 'ibu_hamil')->count();
            $trendVisitsLansia[] = $monthRecords->filter(fn ($r) => $r->patient && $r->patient->category === 'lansia')->count();
        }

        // Compare Mode & Yearly Mode Logic
        $this->trendCompareCurrent = [];
        $this->trendComparePrevious = [];
        $this->trendLabelsPrevious = [];

        if ($this->viewMode === 'yearly') {
            // Yearly mode compares this year vs last year overall visits
            $recordsPrevYear = (clone $medicalRecordQuery)
                ->whereYear('visit_date', $selectedYear - 1)
                ->get();

            for ($m = 1; $m <= 12; $m++) {
                $this->trendCompareCurrent[] = $recordsYear->filter(fn ($r) => Carbon::parse($r->visit_date)->month === $m)->count();
                $this->trendComparePrevious[] = $recordsPrevYear->filter(fn ($r) => Carbon::parse($r->visit_date)->month === $m)->count();
            }
        } elseif ($this->compareMode && $selectedMonth) {
            // Compare mode (Bulan Ini vs Bulan Lalu)
            $recordsPrevMonth = (clone $medicalRecordQuery)
                ->whereYear('visit_date', clone Carbon::create($selectedYear, $selectedMonth)->subMonth()->year)
                ->whereMonth('visit_date', clone Carbon::create($selectedYear, $selectedMonth)->subMonth()->month)
                ->get();

            // Group by category for bar chart
            $categories = ['balita', 'ibu_hamil', 'lansia'];
            foreach ($categories as $cat) {
                $this->trendCompareCurrent[] = $recordsYear->filter(fn ($r) => Carbon::parse($r->visit_date)->month === $selectedMonth && $r->patient && in_array($r->patient->category, $cat === 'balita' ? ['balita', 'bayi', 'baduta'] : [$cat]))->count();
                $this->trendComparePrevious[] = $recordsPrevMonth->filter(fn ($r) => $r->patient && in_array($r->patient->category, $cat === 'balita' ? ['balita', 'bayi', 'baduta'] : [$cat]))->count();
            }
            $this->trendLabelsPrevious = ['Balita', 'Ibu Hamil', 'Lansia'];
        }

        // ── 2. BALITA ANALYTICS ─────────────────────────────────────
        $latestRecordSubquery = MedicalRecord::selectRaw('MAX(id) as id')
            ->whereYear('visit_date', $selectedYear)
            ->when($selectedMonth, fn ($q) => $q->whereMonth('visit_date', $selectedMonth))
            ->groupBy('patient_id');

        $baseBalitaWithRecords = (clone $patientQuery)
            ->whereIn('category', ['balita', 'bayi', 'baduta'])
            ->whereHas('medicalRecords', $basePatientFilter);

        $totalWithRecord = $baseBalitaWithRecords->count();

        $stuntingCount = (clone $baseBalitaWithRecords)
            ->whereHas('medicalRecords', fn ($q) => $q->where(function ($sq) {
                $sq->whereIn('nutrition_status', [
                    MedicalRecord::STATUS_BB_U_SANGAT_KURANG,
                    MedicalRecord::STATUS_BB_U_KURANG,
                ])->orWhereIn('stunting_status', [
                    MedicalRecord::STATUS_TB_U_SANGAT_PENDEK,
                    MedicalRecord::STATUS_TB_U_PENDEK,
                ])->orWhereIn('wasting_status', [
                    MedicalRecord::STATUS_GIZI_BURUK,
                    MedicalRecord::STATUS_GIZI_KURANG,
                ]);
            })->whereYear('visit_date', $selectedYear)
                ->when($selectedMonth, fn ($mq) => $mq->whereMonth('visit_date', $selectedMonth))
                ->whereIn('id', $latestRecordSubquery)
            )
            ->count();

        $stuntingRate = $totalWithRecord > 0 ? round(($stuntingCount / $totalWithRecord) * 100, 1) : 0;

        $balitaWithImunisasi = (clone $medicalRecordQuery)
            ->whereHas('patient', fn ($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))
            ->whereNotNull('immunization')
            ->where('immunization', '!=', '')
            ->whereYear('visit_date', $selectedYear)
            ->when($selectedMonth, fn ($q) => $q->whereMonth('visit_date', $selectedMonth))
            ->distinct('patient_id')
            ->count('patient_id');

        $cakupanImunisasi = $totalBalita > 0 ? round(($balitaWithImunisasi / $totalBalita) * 100, 1) : 0;

        $balitaRecords = (clone $medicalRecordQuery)
            ->whereHas('patient', fn ($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))
            ->whereYear('visit_date', $selectedYear)
            ->select('id', 'visit_date', 'nutrition_status', 'stunting_status', 'wasting_status', 'weight', 'height')
            ->get();

        $balitaTrends = $balitaRecords->groupBy(function ($record) {
            return Carbon::parse($record->visit_date)->month;
        })->map(function ($group) {
            $total = $group->count();
            if ($total === 0) {
                return (object) ['normal_rate' => 0, 'stunting_rate' => 0, 'risk_rate' => 0];
            }

            $normal = $group->whereIn('nutrition_status', [MedicalRecord::STATUS_BB_U_NORMAL, MedicalRecord::STATUS_GIZI_BAIK])->count();
            $stunting = $group->where(function ($r) {
                return in_array($r->nutrition_status, [MedicalRecord::STATUS_BB_U_SANGAT_KURANG, MedicalRecord::STATUS_BB_U_KURANG]) ||
                       in_array($r->stunting_status, [MedicalRecord::STATUS_TB_U_SANGAT_PENDEK, MedicalRecord::STATUS_TB_U_PENDEK]) ||
                       in_array($r->wasting_status, [MedicalRecord::STATUS_GIZI_BURUK, MedicalRecord::STATUS_GIZI_KURANG]);
            })->count();
            $risk = $group->whereIn('nutrition_status', [MedicalRecord::STATUS_BB_U_RISIKO_LEBIH, MedicalRecord::STATUS_GIZI_BERISIKO_LEBIH, MedicalRecord::STATUS_GIZI_LEBIH, MedicalRecord::STATUS_GIZI_OBESITAS])->count();

            return (object) [
                'normal_rate' => round(($normal / $total) * 100, 1),
                'stunting_rate' => round(($stunting / $total) * 100, 1),
                'risk_rate' => round(($risk / $total) * 100, 1),
            ];
        });

        $trendNormal = [];
        $trendStunting = [];
        $trendRisk = [];
        $trendAvgWeight = [];
        $trendAvgHeight = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthData = $balitaTrends->get($m);
            $trendNormal[] = $monthData ? $monthData->normal_rate : 0;
            $trendStunting[] = $monthData ? $monthData->stunting_rate : 0;
            $trendRisk[] = $monthData ? $monthData->risk_rate : 0;

            // Rata-rata BB & TB per bulan
            $monthRecs = $balitaRecords->filter(fn ($r) => Carbon::parse($r->visit_date)->month === $m);
            $weightVals = $monthRecs->map(fn ($r) => (float) ($r->weight ?? 0))->filter(fn ($v) => $v > 0);
            $heightVals = $monthRecs->map(fn ($r) => (float) ($r->height ?? 0))->filter(fn ($v) => $v > 0);
            $trendAvgWeight[] = $weightVals->count() > 0 ? round($weightVals->average(), 2) : 0;
            $trendAvgHeight[] = $heightVals->count() > 0 ? round($heightVals->average(), 2) : 0;
        }

        $dist = (clone $medicalRecordQuery)
            ->whereHas('patient', fn ($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))
            ->whereYear('visit_date', $selectedYear)
            ->when($selectedMonth, fn ($q) => $q->whereMonth('visit_date', $selectedMonth))
            ->whereNotNull('nutrition_status')
            ->select('nutrition_status', DB::raw('COUNT(*) as total'))
            ->groupBy('nutrition_status')
            ->pluck('total', 'nutrition_status')
            ->toArray();

        $sortOrder = [
            'Gizi Baik' => 1,
            'baik' => 2,
            'Gizi Kurang' => 3,
            'Gizi Buruk' => 4,
        ];
        uksort($dist, function ($a, $b) use ($sortOrder) {
            $orderA = $sortOrder[$a] ?? 99;
            $orderB = $sortOrder[$b] ?? 99;

            return $orderA <=> $orderB;
        });

        $posyandus = $this->getAllowedPosyandus();
        $posyanduIds = $posyandus->pluck('id');

        $totalsPerPosyandu = Patient::whereIn('posyandu_id', $posyanduIds)
            ->whereIn('category', ['balita', 'bayi', 'baduta'])
            ->whereHas('medicalRecords', $basePatientFilter)
            ->select('posyandu_id', DB::raw('COUNT(*) as count'))
            ->groupBy('posyandu_id')
            ->pluck('count', 'posyandu_id');

        $stuntingPerPosyandu = Patient::whereIn('posyandu_id', $posyanduIds)
            ->whereIn('category', ['balita', 'bayi', 'baduta'])
            ->whereHas('medicalRecords', fn ($q) => $q->where(function ($sq) {
                $sq->whereIn('nutrition_status', [MedicalRecord::STATUS_BB_U_SANGAT_KURANG, MedicalRecord::STATUS_BB_U_KURANG])
                    ->orWhereIn('stunting_status', [MedicalRecord::STATUS_TB_U_SANGAT_PENDEK, MedicalRecord::STATUS_TB_U_PENDEK])
                    ->orWhereIn('wasting_status', [MedicalRecord::STATUS_GIZI_BURUK, MedicalRecord::STATUS_GIZI_KURANG]);
            })
                ->whereYear('visit_date', $selectedYear)
                ->when($selectedMonth, fn ($mq) => $mq->whereMonth('visit_date', $selectedMonth))
                ->whereIn('id', $latestRecordSubquery)
            )
            ->select('posyandu_id', DB::raw('COUNT(*) as count'))
            ->groupBy('posyandu_id')
            ->pluck('count', 'posyandu_id');

        $stuntingByPosyandu = [];
        foreach ($posyandus as $pos) {
            $total = $totalsPerPosyandu->get($pos->id, 0);
            $stunting = $stuntingPerPosyandu->get($pos->id, 0);
            $rate = $total > 0 ? round(($stunting / $total) * 100, 1) : 0;
            $stuntingByPosyandu[] = [
                'name' => $pos->name, 'rate' => $rate, 'stunting' => $stunting, 'total' => $total,
                'width' => min(100, $rate * 6),
                'color' => $rate >= 10 ? 'bg-red-500' : ($rate >= 5 ? 'bg-amber-500' : 'bg-green-500'),
                'text' => $rate >= 10 ? 'text-red-600' : ($rate >= 5 ? 'text-amber-600' : 'text-green-600'),
            ];
        }

        $balitas = (clone $patientQuery)
            ->whereIn('category', ['balita', 'bayi', 'baduta'])
            ->where('birth_date', '<=', $determinationDate->format('Y-m-d'))
            ->whereHas('medicalRecords', $basePatientFilter)
            ->select('id', 'birth_date')
            ->get();

        $bayis = 0;
        $badutas = 0;
        $balitasCount = 0;
        foreach ($balitas as $b) {
            $months = Carbon::parse($b->birth_date)->diffInMonths($determinationDate);
            if ($months <= 11) {
                $bayis++;
            } elseif ($months <= 23) {
                $badutas++;
            } else {
                $balitasCount++;
            }
        }

        // Vaccine counts
        $vaccineList = ['HB-0', 'BCG', 'Polio 1', 'Polio 2', 'Polio 3', 'Polio 4', 'DPT-HB-Hib 1', 'DPT-HB-Hib 2', 'DPT-HB-Hib 3', 'PCV 1', 'PCV 2', 'PCV 3', 'RV 1', 'RV 2', 'RV 3', 'IPV 1', 'IPV 2', 'MR'];
        $vaccineCounts = [];
        foreach ($vaccineList as $vaxName) {
            $count = (clone $medicalRecordQuery)
                ->whereHas('patient', fn ($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))
                ->whereYear('visit_date', $selectedYear)
                ->when($selectedMonth, fn ($q) => $q->whereMonth('visit_date', $selectedMonth))
                ->where('vaccine_name', 'like', "%{$vaxName}%")
                ->distinct('patient_id')
                ->count('patient_id');
            $vaccineCounts[$vaxName] = $count;
        }
        $vaccineLabels = array_keys($vaccineCounts);
        $vaccineData = array_values($vaccineCounts);

        // ── 3. PREGNANCY (IBU HAMIL) ANALYTICS ─────────────────────
        $latestRecordSubqueryPreg = MedicalRecord::selectRaw('MAX(id) as id')
            ->whereYear('visit_date', $selectedYear)
            ->when($selectedMonth, fn ($q) => $q->whereMonth('visit_date', $selectedMonth))
            ->groupBy('patient_id');

        $pregWithRecords = (clone $patientQuery)
            ->where('category', 'ibu_hamil')
            ->whereHas('medicalRecords', $basePatientFilter);

        $totalPregWithRecordsCount = $pregWithRecords->count();

        $pregHypertensionCount = (clone $pregWithRecords)
            ->whereHas('medicalRecords', fn ($q) => $q->where(function ($sq) {
                $sq->where('systolic_bp', '>=', 140)
                    ->orWhere('diastolic_bp', '>=', 90);
            })->whereIn('id', $latestRecordSubqueryPreg))
            ->count();

        $pregFeComplianceCount = (clone $pregWithRecords)
            ->whereHas('medicalRecords', fn ($q) => $q->where('pill_fe', 1)->whereIn('id', $latestRecordSubqueryPreg))
            ->count();

        $hypertensionRiskRate = $totalPregWithRecordsCount > 0 ? round(($pregHypertensionCount / $totalPregWithRecordsCount) * 100, 1) : 0;
        $feComplianceRate = $totalPregWithRecordsCount > 0 ? round(($pregFeComplianceCount / $totalPregWithRecordsCount) * 100, 1) : 0;

        // Pregnancy trends bulanan
        $trendPregnancyHypertension = [];
        $trendPregnancyFe = [];
        for ($m = 1; $m <= 12; $m++) {
            $latestRecordSubqueryPM = MedicalRecord::selectRaw('MAX(id) as id')
                ->whereYear('visit_date', $selectedYear)
                ->whereMonth('visit_date', $m)
                ->groupBy('patient_id');
            $pregWithRecordsM = (clone $patientQuery)
                ->where('category', 'ibu_hamil')
                ->whereHas('medicalRecords', fn ($q) => $q->whereYear('visit_date', $selectedYear)->whereMonth('visit_date', $m));
            $totalM = $pregWithRecordsM->count();
            if ($totalM > 0) {
                $hyperM = (clone $pregWithRecordsM)
                    ->whereHas('medicalRecords', fn ($q) => $q->where(fn ($sq) => $sq->where('systolic_bp', '>=', 140)->orWhere('diastolic_bp', '>=', 90))->whereIn('id', $latestRecordSubqueryPM))
                    ->count();
                $feM = (clone $pregWithRecordsM)
                    ->whereHas('medicalRecords', fn ($q) => $q->where('pill_fe', 1)->whereIn('id', $latestRecordSubqueryPM))
                    ->count();
                $trendPregnancyHypertension[] = round(($hyperM / $totalM) * 100, 1);
                $trendPregnancyFe[] = round(($feM / $totalM) * 100, 1);
            } else {
                $trendPregnancyHypertension[] = 0;
                $trendPregnancyFe[] = 0;
            }
        }

        // ── 4. LANSIA ANALYTICS ────────────────────────────────────
        $latestRecordSubqueryLansia = MedicalRecord::selectRaw('MAX(id) as id')
            ->whereYear('visit_date', $selectedYear)
            ->when($selectedMonth, fn ($q) => $q->whereMonth('visit_date', $selectedMonth))
            ->groupBy('patient_id');

        $lansiaWithRecords = (clone $patientQuery)
            ->where('category', 'lansia')
            ->whereHas('medicalRecords', $basePatientFilter);

        $totalLansiaWithRecordsCount = $lansiaWithRecords->count();

        $lansiaHypertensionCount = (clone $lansiaWithRecords)
            ->whereHas('medicalRecords', fn ($q) => $q->where(function ($sq) {
                $sq->where('systolic_bp', '>=', 140)
                    ->orWhere('diastolic_bp', '>=', 90);
            })->whereIn('id', $latestRecordSubqueryLansia))
            ->count();

        $lansiaHyperglycemiaCount = (clone $lansiaWithRecords)
            ->whereHas('medicalRecords', fn ($q) => $q->where('blood_sugar', '>=', 200)->whereIn('id', $latestRecordSubqueryLansia))
            ->count();

        $lansiaHypercholesterolemiaCount = (clone $lansiaWithRecords)
            ->whereHas('medicalRecords', fn ($q) => $q->where('cholesterol', '>=', 200)->whereIn('id', $latestRecordSubqueryLansia))
            ->count();

        $lansiaHyperuricemiaCount = (clone $lansiaWithRecords)
            ->whereHas('medicalRecords', fn ($q) => $q->where('uric_acid', '>=', 7.0)->whereIn('id', $latestRecordSubqueryLansia))
            ->count();

        $lansiaHypertensionRate = $totalLansiaWithRecordsCount > 0 ? round(($lansiaHypertensionCount / $totalLansiaWithRecordsCount) * 100, 1) : 0;
        $lansiaHyperglycemiaRate = $totalLansiaWithRecordsCount > 0 ? round(($lansiaHyperglycemiaCount / $totalLansiaWithRecordsCount) * 100, 1) : 0;
        $lansiaHypercholesterolemiaRate = $totalLansiaWithRecordsCount > 0 ? round(($lansiaHypercholesterolemiaCount / $totalLansiaWithRecordsCount) * 100, 1) : 0;
        $lansiaHyperuricemiaRate = $totalLansiaWithRecordsCount > 0 ? round(($lansiaHyperuricemiaCount / $totalLansiaWithRecordsCount) * 100, 1) : 0;

        // Lansia trends bulanan & averages
        $trendLansiaHypertension = [];
        $trendLansiaHyperglycemia = [];
        $trendLansiaHypercholesterolemia = [];
        $trendLansiaHyperuricemia = [];

        $trendLansiaAvgSystolic = [];
        $trendLansiaAvgDiastolic = [];
        $trendLansiaAvgBloodSugar = [];
        $trendLansiaAvgUricAcid = [];
        $trendLansiaAvgCholesterol = [];

        $trendPregnancyAvgWeightGain = [];
        $trendPregnancyAvgLila = [];

        $lansiaRecordsYear = $recordsYear->filter(fn ($r) => $r->patient && $r->patient->category === 'lansia');
        $pregRecordsYear = $recordsYear->filter(fn ($r) => $r->patient && $r->patient->category === 'ibu_hamil');

        for ($m = 1; $m <= 12; $m++) {
            $latestRecordSubqueryLM = MedicalRecord::selectRaw('MAX(id) as id')
                ->whereYear('visit_date', $selectedYear)
                ->whereMonth('visit_date', $m)
                ->groupBy('patient_id');
            $lansiaWithRecordsM = (clone $patientQuery)
                ->where('category', 'lansia')
                ->whereHas('medicalRecords', fn ($q) => $q->whereYear('visit_date', $selectedYear)->whereMonth('visit_date', $m));
            $totalLM = $lansiaWithRecordsM->count();
            if ($totalLM > 0) {
                $hyperLM = (clone $lansiaWithRecordsM)
                    ->whereHas('medicalRecords', fn ($q) => $q->where(fn ($sq) => $sq->where('systolic_bp', '>=', 140)->orWhere('diastolic_bp', '>=', 90))->whereIn('id', $latestRecordSubqueryLM))
                    ->count();
                $sugarLM = (clone $lansiaWithRecordsM)
                    ->whereHas('medicalRecords', fn ($q) => $q->where('blood_sugar', '>=', 200)->whereIn('id', $latestRecordSubqueryLM))
                    ->count();
                $cholLM = (clone $lansiaWithRecordsM)
                    ->whereHas('medicalRecords', fn ($q) => $q->where('cholesterol', '>=', 200)->whereIn('id', $latestRecordSubqueryLM))
                    ->count();
                $uricLM = (clone $lansiaWithRecordsM)
                    ->whereHas('medicalRecords', fn ($q) => $q->where('uric_acid', '>=', 7.0)->whereIn('id', $latestRecordSubqueryLM))
                    ->count();
                $trendLansiaHypertension[] = round(($hyperLM / $totalLM) * 100, 1);
                $trendLansiaHyperglycemia[] = round(($sugarLM / $totalLM) * 100, 1);
                $trendLansiaHypercholesterolemia[] = round(($cholLM / $totalLM) * 100, 1);
                $trendLansiaHyperuricemia[] = round(($uricLM / $totalLM) * 100, 1);
            } else {
                $trendLansiaHypertension[] = 0;
                $trendLansiaHyperglycemia[] = 0;
                $trendLansiaHypercholesterolemia[] = 0;
                $trendLansiaHyperuricemia[] = 0;
            }

            // Averages calculations from cached records
            $monthLansiaRecs = $lansiaRecordsYear->filter(fn ($r) => Carbon::parse($r->visit_date)->month === $m);
            $monthPregRecs = $pregRecordsYear->filter(fn ($r) => Carbon::parse($r->visit_date)->month === $m);

            // Lansia averages
            $sysVals = $monthLansiaRecs->pluck('systolic_bp')->filter(fn ($v) => (float) $v > 0);
            $trendLansiaAvgSystolic[] = $sysVals->count() > 0 ? round($sysVals->average(), 1) : 0;

            $diaVals = $monthLansiaRecs->pluck('diastolic_bp')->filter(fn ($v) => (float) $v > 0);
            $trendLansiaAvgDiastolic[] = $diaVals->count() > 0 ? round($diaVals->average(), 1) : 0;

            $sugarVals = $monthLansiaRecs->pluck('blood_sugar')->filter(fn ($v) => (float) $v > 0);
            $trendLansiaAvgBloodSugar[] = $sugarVals->count() > 0 ? round($sugarVals->average(), 1) : 0;

            $uricVals = $monthLansiaRecs->pluck('uric_acid')->filter(fn ($v) => (float) $v > 0);
            $trendLansiaAvgUricAcid[] = $uricVals->count() > 0 ? round($uricVals->average(), 2) : 0;

            $cholVals = $monthLansiaRecs->pluck('cholesterol')->filter(fn ($v) => (float) $v > 0);
            $trendLansiaAvgCholesterol[] = $cholVals->count() > 0 ? round($cholVals->average(), 1) : 0;

            // Ibu Hamil averages
            $gains = [];
            foreach ($monthPregRecs as $rec) {
                if ((float) $rec->weight > 0) {
                    $startW = (float) $rec->starting_weight;
                    if ($startW <= 0) {
                        $patientRecs = $pregRecordsYear->filter(fn ($r) => $r->patient_id === $rec->patient_id)->sortBy('visit_date');
                        $firstWeight = $patientRecs->first()?->weight ?? $rec->weight;
                        $startW = $patientRecs->where('starting_weight', '>', 0)->first()?->starting_weight ?? $firstWeight;
                    }
                    $gains[] = max(0.0, (float) $rec->weight - (float) $startW);
                }
            }
            $trendPregnancyAvgWeightGain[] = count($gains) > 0 ? round(array_sum($gains) / count($gains), 1) : 0;

            $lilaVals = $monthPregRecs->pluck('upper_arm_circumference')->filter(fn ($v) => (float) $v > 0);
            $trendPregnancyAvgLila[] = $lilaVals->count() > 0 ? round($lilaVals->average(), 1) : 0;
        }

        return [
            // Overview
            'totalBalita' => $totalBalita,
            'totalIbuHamil' => $totalIbuHamil,
            'totalLansia' => $totalLansia,
            'totalKunjungan' => $totalKunjungan,
            'kaderAktif' => $kaderAktif,
            'trendLabels' => $trendLabels,
            'trendVisitsBalita' => $trendVisitsBalita,
            'trendVisitsIbuHamil' => $trendVisitsIbuHamil,
            'trendVisitsLansia' => $trendVisitsLansia,
            // Balita
            'stuntingRate' => $stuntingRate,
            'cakupanImunisasi' => $cakupanImunisasi,
            'trendNormal' => $trendNormal,
            'trendStunting' => $trendStunting,
            'trendRisk' => $trendRisk,
            'trendAvgWeight' => $trendAvgWeight,
            'trendAvgHeight' => $trendAvgHeight,
            'nutritionLabels' => array_keys($dist),
            'nutritionData' => array_values($dist),
            'stuntingByPosyandu' => $stuntingByPosyandu,
            'usia0_12' => $bayis,
            'usia12_24' => $badutas,
            'usia24plus' => $balitasCount,
            'vaccineLabels' => $vaccineLabels,
            'vaccineData' => $vaccineData,
            // Ibu Hamil
            'hypertensionRiskRate' => $hypertensionRiskRate,
            'feComplianceRate' => $feComplianceRate,
            'trendPregnancyHypertension' => $trendPregnancyHypertension,
            'trendPregnancyFe' => $trendPregnancyFe,
            'trendPregnancyAvgWeightGain' => $trendPregnancyAvgWeightGain,
            'trendPregnancyAvgLila' => $trendPregnancyAvgLila,
            // Lansia
            'lansiaHypertensionRate' => $lansiaHypertensionRate,
            'lansiaHyperglycemiaRate' => $lansiaHyperglycemiaRate,
            'lansiaHypercholesterolemiaRate' => $lansiaHypercholesterolemiaRate,
            'lansiaHyperuricemiaRate' => $lansiaHyperuricemiaRate,
            'trendLansiaHypertension' => $trendLansiaHypertension,
            'trendLansiaHyperglycemia' => $trendLansiaHyperglycemia,
            'trendLansiaHypercholesterolemia' => $trendLansiaHypercholesterolemia,
            'trendLansiaHyperuricemia' => $trendLansiaHyperuricemia,
            'trendLansiaAvgSystolic' => $trendLansiaAvgSystolic,
            'trendLansiaAvgDiastolic' => $trendLansiaAvgDiastolic,
            'trendLansiaAvgBloodSugar' => $trendLansiaAvgBloodSugar,
            'trendLansiaAvgUricAcid' => $trendLansiaAvgUricAcid,
            'trendLansiaAvgCholesterol' => $trendLansiaAvgCholesterol,
        ];
    }

    public function render()
    {
        return view('livewire.admin.analytics');
    }
}
