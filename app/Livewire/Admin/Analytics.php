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

    // Overview stats
    public int $totalBalita = 0;

    public float $stuntingRate = 0;

    public float $cakupanImunisasi = 0;

    public int $kaderAktif = 0;

    // Trend data (12 bulan)
    public array $trendLabels = [];

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
    public $recentRecords;

    public ?int $selectedMonth = null; // null means full year

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

    /**
     * Refresh data manually (clears cache)
     */
    protected function loadData(): void
    {
        $user = Auth::user();
        $posyanduId = $user->isSuperAdmin() ? null : $user->posyandu_id;
        $key = "year_{$this->selectedYear}".($this->selectedMonth ? "_month_{$this->selectedMonth}" : '');

        $snapshot = \App\Models\AnalyticsSnapshot::where('posyandu_id', $posyanduId)
            ->where('key', $key)
            ->first();

        if ($snapshot) {
            $data = $snapshot->data['analytics_data'];
            $this->lastUpdated = $snapshot->last_computed_at->format('d M Y H:i');
        } else {
            // Fallback to legacy computation if no snapshot exists
            $data = $this->fetchAnalyticsData();
            $this->lastUpdated = 'Live (Memproses Snapshot...)';

            // Dispatch job to create snapshot for next time
            ComputeAnalyticsSnapshot::dispatch($posyanduId, $this->selectedYear, $this->selectedMonth);
        }

        foreach ($data as $k => $value) {
            if (property_exists($this, $k)) {
                $this->{$k} = $value;
            }
        }

        // Dispatch event to update charts in frontend
        $this->dispatch('charts-updated',
            trendLabels: $this->trendLabels,
            trendNormal: $this->trendNormal,
            trendStunting: $this->trendStunting,
            trendRisk: $this->trendRisk,
            nutritionLabels: $this->nutritionLabels,
            nutritionData: $this->nutritionData
        );

        // Recent records (filtered by month/year)
        $medicalRecordQuery = $this->applyPosyanduScope(MedicalRecord::query())
            ->whereYear('visit_date', $this->selectedYear);

        if ($this->selectedMonth) {
            $medicalRecordQuery->whereMonth('visit_date', $this->selectedMonth);
        }

        $this->recentRecords = $medicalRecordQuery
            ->with(['patient.posyandu'])
            ->whereHas('patient', fn ($q) => $q->where('category', 'balita'))
            ->latest('visit_date')
            ->limit(5)
            ->get();
    }

    protected function fetchAnalyticsData(): array
    {
        $user = Auth::user();
        $patientQuery = $this->applyPosyanduScope(Patient::query());
        $medicalRecordQuery = $this->applyPosyanduScope(MedicalRecord::query());

        // ── Overview Stats ──────────────────────────────────────────
        $selectedYear = $this->selectedYear;
        $selectedMonth = $this->selectedMonth;

        // Determination date for age calculation (end of year or end of selected month)
        $determinationDate = $selectedMonth
            ? Carbon::create($selectedYear, $selectedMonth)->endOfMonth()
            : Carbon::create($selectedYear)->endOfYear();

        // Total Balita who had at least one visit in the selected period
        $basePatientFilter = fn ($q) => $q->whereYear('visit_date', $selectedYear)
            ->when($selectedMonth, fn ($mq) => $mq->whereMonth('visit_date', $selectedMonth));

        $totalBalita = (clone $patientQuery)
            ->where('category', 'balita')
            ->whereHas('medicalRecords', $basePatientFilter)
            ->count();

        // Stunting rate: latest record per patient WITHIN the selected period
        $latestRecordSubquery = MedicalRecord::selectRaw('MAX(id) as id')
            ->whereYear('visit_date', $selectedYear)
            ->when($selectedMonth, fn ($q) => $q->whereMonth('visit_date', $selectedMonth))
            ->groupBy('patient_id');

        $baseBalitaWithRecords = (clone $patientQuery)
            ->where('category', 'balita')
            ->whereHas('medicalRecords', $basePatientFilter);

        $totalWithRecord = (clone $baseBalitaWithRecords)->count();

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
            })->whereIn('id', $latestRecordSubquery)
            )
            ->count();

        $stuntingRate = $totalWithRecord > 0 ? round(($stuntingCount / $totalWithRecord) * 100, 1) : 0;

        // Cakupan Imunisasi
        $balitaWithImunisasi = (clone $medicalRecordQuery)
            ->whereHas('patient', fn ($q) => $q->where('category', 'balita'))
            ->whereNotNull('immunization')
            ->where('immunization', '!=', '')
            ->whereYear('visit_date', $selectedYear)
            ->when($selectedMonth, fn ($q) => $q->whereMonth('visit_date', $selectedMonth))
            ->distinct('patient_id')
            ->count('patient_id');

        $cakupanImunisasi = $totalBalita > 0 ? round(($balitaWithImunisasi / $totalBalita) * 100, 1) : 0;

        // Kader Aktif (Global, but we keep it)
        $kaderAktif = User::where('is_active', true)
            ->whereIn('role', ['staff', 'medical', 'admin'])
            ->when(! $user->isSuperAdmin() && $user->posyandu_id, fn ($q) => $q->where('posyandu_id', $user->posyandu_id)
            )
            ->count();

        // ── Trend 12 Bulan ───────────────────────────────────────────
        $records = (clone $medicalRecordQuery)
            ->whereHas('patient', fn ($q) => $q->where('category', 'balita'))
            ->whereYear('visit_date', $this->selectedYear)
            ->select('id', 'visit_date', 'nutrition_status')
            ->get();

        $trends = $records->groupBy(function ($record) {
            return Carbon::parse($record->visit_date)->month;
        })->map(function ($group) {
            $normal = $group->whereIn('nutrition_status', [MedicalRecord::STATUS_BB_U_NORMAL, MedicalRecord::STATUS_GIZI_BAIK])->count();
            $stunting = $group->where(function ($r) {
                return in_array($r->nutrition_status, [MedicalRecord::STATUS_BB_U_SANGAT_KURANG, MedicalRecord::STATUS_BB_U_KURANG]) ||
                       in_array($r->stunting_status, [MedicalRecord::STATUS_TB_U_SANGAT_PENDEK, MedicalRecord::STATUS_TB_U_PENDEK]) ||
                       in_array($r->wasting_status, [MedicalRecord::STATUS_GIZI_BURUK, MedicalRecord::STATUS_GIZI_KURANG]);
            })->count();
            $risk = $group->whereIn('nutrition_status', [MedicalRecord::STATUS_BB_U_RISIKO_LEBIH, MedicalRecord::STATUS_GIZI_BERISIKO_LEBIH, MedicalRecord::STATUS_GIZI_LEBIH, MedicalRecord::STATUS_GIZI_OBESITAS])->count();

            return clone (object) [
                'normal_count' => $normal,
                'stunting_count' => $stunting,
                'risk_count' => $risk,
            ];
        });

        $trendLabels = [];
        $trendNormal = [];
        $trendStunting = [];
        $trendRisk = [];
        for ($m = 1; $m <= 12; $m++) {
            $trendLabels[] = Carbon::create($this->selectedYear, $m)->translatedFormat('M');
            $trendNormal[] = $trends->get($m)->normal_count ?? 0;
            $trendStunting[] = $trends->get($m)->stunting_count ?? 0;
            $trendRisk[] = $trends->get($m)->risk_count ?? 0;
        }

        // ── Distribusi Status Gizi ───────────────────────────────────
        $dist = (clone $medicalRecordQuery)
            ->whereHas('patient', fn ($q) => $q->where('category', 'balita'))
            ->whereYear('visit_date', $this->selectedYear)
            ->when($selectedMonth, fn ($q) => $q->whereMonth('visit_date', $selectedMonth))
            ->whereNotNull('nutrition_status')
            ->select('nutrition_status', DB::raw('COUNT(*) as total'))
            ->groupBy('nutrition_status')
            ->pluck('total', 'nutrition_status')
            ->toArray();

        // ── Stunting per Posyandu ─────────────────────────────────────
        $posyandus = $this->getAllowedPosyandus();
        $posyanduIds = $posyandus->pluck('id');

        $totalsPerPosyandu = Patient::whereIn('posyandu_id', $posyanduIds)
            ->where('category', 'balita')
            ->whereHas('medicalRecords', $basePatientFilter)
            ->select('posyandu_id', DB::raw('COUNT(*) as count'))
            ->groupBy('posyandu_id')
            ->pluck('count', 'posyandu_id');

        $stuntingPerPosyandu = Patient::whereIn('posyandu_id', $posyanduIds)
            ->where('category', 'balita')
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
                'name' => $pos->name,
                'rate' => $rate,
                'stunting' => $stunting,
                'total' => $total,
                'width' => min(100, $rate * 6),
                'color' => $rate >= 10 ? 'bg-red-500' : ($rate >= 5 ? 'bg-amber-500' : 'bg-green-500'),
                'text' => $rate >= 10 ? 'text-red-600' : ($rate >= 5 ? 'text-amber-600' : 'text-green-600'),
            ];
        }

        // ── Demographics (Ages relative to the period) ───────────────
        $balitas = (clone $patientQuery)
            ->where('category', 'balita')
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

        return [
            'totalBalita' => $totalBalita,
            'stuntingRate' => $stuntingRate,
            'cakupanImunisasi' => $cakupanImunisasi,
            'kaderAktif' => $kaderAktif,
            'trendLabels' => $trendLabels,
            'trendNormal' => $trendNormal,
            'trendStunting' => $trendStunting,
            'trendRisk' => $trendRisk,
            'nutritionLabels' => array_keys($dist),
            'nutritionData' => array_values($dist),
            'stuntingByPosyandu' => $stuntingByPosyandu,
            'usia0_12' => $bayis,
            'usia12_24' => $badutas,
            'usia24plus' => $balitasCount,
        ];
    }

    public function render()
    {
        return view('livewire.admin.analytics');
    }
}
