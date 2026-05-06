<?php

namespace App\Jobs;

use App\Models\AnalyticsSnapshot;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Posyandu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class ComputeAnalyticsSnapshot implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ?int $posyanduId = null,
        public int $year = 0,
        public ?int $month = null
    ) {
        $this->year = $year ?: (int) now()->year;
    }

    public function handle(): void
    {
        $data = [
            'analytics_data' => $this->computeAnalyticsData(),
            'dashboard_stats' => $this->computeDashboardStats(),
        ];

        $key = "year_{$this->year}".($this->month ? "_month_{$this->month}" : '');

        AnalyticsSnapshot::updateOrCreate(
            ['posyandu_id' => $this->posyanduId, 'key' => $key],
            ['data' => $data, 'last_computed_at' => now()]
        );
    }

    protected function computeAnalyticsData(): array
    {
        $patientQuery = Patient::query();
        $medicalRecordQuery = MedicalRecord::query();

        if ($this->posyanduId) {
            $patientQuery->where('posyandu_id', $this->posyanduId);
            $medicalRecordQuery->whereHas('patient', fn ($q) => $q->where('posyandu_id', $this->posyanduId));
        }

        // --- Copied logic from Analytics.php ---
        $year = $this->year;
        $month = $this->month; // Note: Ensure the class has public ?int $month property

        // Determination date for age calculation
        $determinationDate = $month
            ? Carbon::create($year, $month)->endOfMonth()
            : Carbon::create($year)->endOfYear();

        $basePatientFilter = fn ($q) => $q->whereYear('visit_date', $year)
            ->when($month, fn ($mq) => $mq->whereMonth('visit_date', $month));

        // Total Balita who had at least one visit in the target period
        $totalBalita = (clone $patientQuery)
            ->whereIn('category', ['balita', 'bayi', 'baduta'])
            ->whereHas('medicalRecords', $basePatientFilter)
            ->count();

        // Stunting rate: latest record per patient WITHIN the target period
        $latestRecordSubquery = MedicalRecord::selectRaw('MAX(id) as id')
            ->whereYear('visit_date', $year)
            ->when($month, fn ($q) => $q->whereMonth('visit_date', $month))
            ->groupBy('patient_id');

        $baseBalitaWithRecords = (clone $patientQuery)
            ->whereIn('category', ['balita', 'bayi', 'baduta'])
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
            })->whereYear('visit_date', $year)
                ->when($month, fn ($mq) => $mq->whereMonth('visit_date', $month))
                ->whereIn('id', $latestRecordSubquery)
            )
            ->count();

        $stuntingRate = $totalWithRecord > 0 ? round(($stuntingCount / $totalWithRecord) * 100, 1) : 0;

        $balitaWithImunisasi = (clone $medicalRecordQuery)
            ->whereHas('patient', fn($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))
            ->whereNotNull('immunization')
            ->where('immunization', '!=', '')
            ->whereYear('visit_date', $year)
            ->when($month, fn ($q) => $q->whereMonth('visit_date', $month))
            ->distinct('patient_id')
            ->count('patient_id');

        $cakupanImunisasi = $totalBalita > 0 ? round(($balitaWithImunisasi / $totalBalita) * 100, 1) : 0;

        $kaderAktif = User::where('is_active', true)
            ->whereIn('role', ['staff', 'medical', 'admin'])
            ->when($this->posyanduId, fn ($q) => $q->where('posyandu_id', $this->posyanduId))
            ->count();

        $records = (clone $medicalRecordQuery)
            ->whereHas('patient', fn($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))
            ->whereYear('visit_date', $year)
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

            return clone (object) ['normal_count' => $normal, 'stunting_count' => $stunting];
        });

        $trendLabels = [];
        $trendNormal = [];
        $trendStunting = [];
        for ($m = 1; $m <= 12; $m++) {
            $trendLabels[] = Carbon::create($year, $m)->translatedFormat('M');
            $trendNormal[] = $trends->get($m)->normal_count ?? 0;
            $trendStunting[] = $trends->get($m)->stunting_count ?? 0;
        }

        $dist = (clone $medicalRecordQuery)
            ->whereHas('patient', fn($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))
            ->whereYear('visit_date', $year)
            ->when($month, fn ($q) => $q->whereMonth('visit_date', $month))
            ->whereNotNull('nutrition_status')
            ->select('nutrition_status', DB::raw('COUNT(*) as total'))
            ->groupBy('nutrition_status')
            ->pluck('total', 'nutrition_status')
            ->toArray();

        $stuntingByPosyandu = [];
        if (! $this->posyanduId) {
            $posyandus = Posyandu::all();
            $posyanduIds = $posyandus->pluck('id');

            $totalsPerPosyandu = Patient::whereIn('posyandu_id', $posyanduIds)
                ->whereIn('category', ['balita', 'bayi', 'baduta'])
                ->whereHas('medicalRecords', $basePatientFilter)
                ->select('posyandu_id', DB::raw('COUNT(*) as count'))
                ->groupBy('posyandu_id')
                ->pluck('count', 'posyandu_id');

            $stuntingPerPosyandu = Patient::whereIn('posyandu_id', $posyanduIds)
                ->whereIn('category', ['balita', 'bayi', 'baduta'])
                ->whereHas('medicalRecords', fn($q) => $q->where(function ($sq) {
                    $sq->whereIn('nutrition_status', [MedicalRecord::STATUS_BB_U_SANGAT_KURANG, MedicalRecord::STATUS_BB_U_KURANG])
                        ->orWhereIn('stunting_status', [MedicalRecord::STATUS_TB_U_SANGAT_PENDEK, MedicalRecord::STATUS_TB_U_PENDEK])
                        ->orWhereIn('wasting_status', [MedicalRecord::STATUS_GIZI_BURUK, MedicalRecord::STATUS_GIZI_KURANG]);
                })
                    ->whereYear('visit_date', $year)
                    ->when($month, fn ($mq) => $mq->whereMonth('visit_date', $month))
                    ->whereIn('id', $latestRecordSubquery)
                )
                ->select('posyandu_id', DB::raw('COUNT(*) as count'))
                ->groupBy('posyandu_id')
                ->pluck('count', 'posyandu_id');

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

        return [
            'totalBalita' => $totalBalita,
            'stuntingRate' => $stuntingRate,
            'cakupanImunisasi' => $cakupanImunisasi,
            'kaderAktif' => $kaderAktif,
            'trendLabels' => $trendLabels,
            'trendNormal' => $trendNormal,
            'trendStunting' => $trendStunting,
            'nutritionLabels' => array_keys($dist),
            'nutritionData' => array_values($dist),
            'stuntingByPosyandu' => $stuntingByPosyandu,
            'usia0_12' => $bayis,
            'usia12_24' => $badutas,
            'usia24plus' => $balitasCount,
        ];
    }

    protected function computeDashboardStats(): array
    {
        $patientQuery = Patient::query();
        $medicalRecordQuery = MedicalRecord::query();

        if ($this->posyanduId) {
            $patientQuery->where('posyandu_id', $this->posyanduId);
            $medicalRecordQuery->whereHas('patient', fn ($q) => $q->where('posyandu_id', $this->posyanduId));
        }

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $counts = (clone $patientQuery)
            ->selectRaw('COUNT(CASE WHEN category IN ("balita", "bayi", "baduta") THEN 1 END) as balita')
            ->selectRaw('COUNT(CASE WHEN category = "ibu_hamil" THEN 1 END) as ibu_hamil')
            ->selectRaw('COUNT(CASE WHEN category = "remaja" THEN 1 END) as remaja')
            ->selectRaw('COUNT(CASE WHEN category = "lansia" THEN 1 END) as lansia')
            ->first();

        $kunjunganBaru = (clone $medicalRecordQuery)
            ->whereMonth('visit_date', $currentMonth)
            ->whereYear('visit_date', $currentYear)
            ->count();

        $latestRecordSubquery = MedicalRecord::selectRaw('MAX(id) as id')->groupBy('patient_id');

        $distribution = (clone $medicalRecordQuery)
            ->whereIn('id', $latestRecordSubquery)
            ->whereHas('patient', fn($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))
            ->whereNotNull('nutrition_status')
            ->select('nutrition_status', DB::raw('COUNT(*) as total'))
            ->groupBy('nutrition_status')
            ->pluck('total', 'nutrition_status');

        $startDate = now()->subMonths(11)->startOfMonth();

        $dateFormat = config('database.default') === 'sqlite'
            ? "strftime('%m %Y', visit_date)"
            : "DATE_FORMAT(visit_date, '%m %Y')";

        $trends = (clone $medicalRecordQuery)
            ->where('visit_date', '>=', $startDate)
            ->selectRaw("$dateFormat as month_year")
            ->selectRaw('COUNT(*) as total')
            ->groupBy('month_year')
            ->get()
            ->pluck('total', 'month_year');

        $labels = [];
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $carbon = now()->subMonths($i);
            $label = $carbon->translatedFormat('M Y');
            $key = $carbon->format('m Y');
            $labels[] = $label;
            $data[] = $trends->get($key, 0);
        }

        return [
            'totalBalita' => $counts->balita ?? 0,
            'totalIbuHamil' => $counts->ibu_hamil ?? 0,
            'totalRemaja' => $counts->remaja ?? 0,
            'totalLansia' => $counts->lansia ?? 0,
            'kunjunganBaru' => $kunjunganBaru,
            'nutritionStatusDistribution' => [
                'labels' => $distribution->keys()->toArray(),
                'data' => $distribution->values()->toArray(),
            ],
            'monthlyWeighingData' => ['labels' => $labels, 'data' => $data],
        ];
    }
}
