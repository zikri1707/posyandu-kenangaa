<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Models\AnalyticsSnapshot;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Posyandu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ComputeAnalyticsSnapshot implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ?int $posyanduId = null,
        public int $year = 0
    ) {
        $this->year = $year ?: (int) now()->year;
    }

    public function handle(): void
    {
        $data = [
            'analytics_data' => $this->computeAnalyticsData(),
            'dashboard_stats' => $this->computeDashboardStats(),
        ];

        AnalyticsSnapshot::updateOrCreate(
            ['posyandu_id' => $this->posyanduId, 'key' => "year_{$this->year}"],
            ['data' => $data, 'last_computed_at' => now()]
        );
    }

    protected function computeAnalyticsData(): array
    {
        $patientQuery = Patient::query();
        $medicalRecordQuery = MedicalRecord::query();

        if ($this->posyanduId) {
            $patientQuery->where('posyandu_id', $this->posyanduId);
            $medicalRecordQuery->whereHas('patient', fn($q) => $q->where('posyandu_id', $this->posyanduId));
        }

        // --- Copied logic from Analytics.php ---
        $totalBalita = (clone $patientQuery)->where('category', 'balita')->count();

        $latestRecordSubquery = MedicalRecord::selectRaw('MAX(id) as id')->groupBy('patient_id');

        $baseBalitaWithRecords = (clone $patientQuery)
            ->where('category', 'balita')
            ->whereHas('medicalRecords');

        $totalWithRecord = (clone $baseBalitaWithRecords)->count();

        $stuntingCount = (clone $baseBalitaWithRecords)
            ->whereHas('medicalRecords', fn($q) =>
                $q->whereIn('nutrition_status', [
                    MedicalRecord::NUTRITION_STUNTING,
                    MedicalRecord::NUTRITION_GIZI_BURUK
                ])->whereIn('id', $latestRecordSubquery)
            )
            ->count();

        $stuntingRate = $totalWithRecord > 0 ? round(($stuntingCount / $totalWithRecord) * 100, 1) : 0;

        $balitaWithImunisasi = (clone $medicalRecordQuery)
            ->whereHas('patient', fn($q) => $q->where('category', 'balita'))
            ->whereNotNull('immunization')
            ->where('immunization', '!=', '')
            ->whereYear('visit_date', $this->year)
            ->distinct('patient_id')
            ->count('patient_id');

        $cakupanImunisasi = $totalBalita > 0 ? round(($balitaWithImunisasi / $totalBalita) * 100, 1) : 0;

        $kaderAktif = User::where('is_active', true)
            ->whereIn('role', ['staff', 'medical', 'admin'])
            ->when($this->posyanduId, fn($q) => $q->where('posyandu_id', $this->posyanduId))
            ->count();

        $trends = (clone $medicalRecordQuery)
            ->whereHas('patient', fn($q) => $q->where('category', 'balita'))
            ->whereYear('visit_date', $this->year)
            ->selectRaw('MONTH(visit_date) as month')
            ->selectRaw('COUNT(CASE WHEN nutrition_status IN (?, ?) THEN 1 END) as normal_count', [
                MedicalRecord::NUTRITION_NORMAL,
                MedicalRecord::NUTRITION_GIZI_BAIK
            ])
            ->selectRaw('COUNT(CASE WHEN nutrition_status IN (?, ?) THEN 1 END) as stunting_count', [
                MedicalRecord::NUTRITION_STUNTING,
                MedicalRecord::NUTRITION_GIZI_BURUK
            ])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $trendLabels = []; $trendNormal = []; $trendStunting = [];
        for ($m = 1; $m <= 12; $m++) {
            $trendLabels[] = Carbon::create($this->year, $m)->translatedFormat('M');
            $trendNormal[] = $trends->get($m)->normal_count ?? 0;
            $trendStunting[] = $trends->get($m)->stunting_count ?? 0;
        }

        $dist = (clone $medicalRecordQuery)
            ->whereHas('patient', fn($q) => $q->where('category', 'balita'))
            ->whereYear('visit_date', $this->year)
            ->whereNotNull('nutrition_status')
            ->select('nutrition_status', DB::raw('COUNT(*) as total'))
            ->groupBy('nutrition_status')
            ->pluck('total', 'nutrition_status')
            ->toArray();

        $stuntingByPosyandu = [];
        if (!$this->posyanduId) {
            $posyandus = Posyandu::all();
            $posyanduIds = $posyandus->pluck('id');

            $totalsPerPosyandu = Patient::whereIn('posyandu_id', $posyanduIds)
                ->where('category', 'balita')
                ->whereHas('medicalRecords')
                ->select('posyandu_id', DB::raw('COUNT(*) as count'))
                ->groupBy('posyandu_id')
                ->pluck('count', 'posyandu_id');

            $stuntingPerPosyandu = Patient::whereIn('posyandu_id', $posyanduIds)
                ->where('category', 'balita')
                ->whereHas('medicalRecords', fn($q) =>
                    $q->whereIn('nutrition_status', [MedicalRecord::NUTRITION_STUNTING, MedicalRecord::NUTRITION_GIZI_BURUK])
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

        $demographics = (clone $patientQuery)
            ->where('category', 'balita')
            ->selectRaw('COUNT(CASE WHEN TIMESTAMPDIFF(MONTH, birth_date, CURDATE()) BETWEEN 0 AND 11 THEN 1 END) as bayis')
            ->selectRaw('COUNT(CASE WHEN TIMESTAMPDIFF(MONTH, birth_date, CURDATE()) BETWEEN 12 AND 23 THEN 1 END) as badutas')
            ->selectRaw('COUNT(CASE WHEN TIMESTAMPDIFF(MONTH, birth_date, CURDATE()) >= 24 THEN 1 END) as balitas')
            ->first();

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
            'usia0_12' => $demographics->bayis ?? 0,
            'usia12_24' => $demographics->badutas ?? 0,
            'usia24plus' => $demographics->balitas ?? 0,
        ];
    }

    protected function computeDashboardStats(): array
    {
        $patientQuery = Patient::query();
        $medicalRecordQuery = MedicalRecord::query();

        if ($this->posyanduId) {
            $patientQuery->where('posyandu_id', $this->posyanduId);
            $medicalRecordQuery->whereHas('patient', fn($q) => $q->where('posyandu_id', $this->posyanduId));
        }

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $counts = (clone $patientQuery)
            ->selectRaw('COUNT(CASE WHEN category = "balita" THEN 1 END) as balita')
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
            ->whereHas('patient', fn($q) => $q->where('category', 'balita'))
            ->whereNotNull('nutrition_status')
            ->select('nutrition_status', DB::raw('COUNT(*) as total'))
            ->groupBy('nutrition_status')
            ->pluck('total', 'nutrition_status');

        $startDate = now()->subMonths(11)->startOfMonth();
        $trends = (clone $medicalRecordQuery)
            ->where('visit_date', '>=', $startDate)
            ->selectRaw('DATE_FORMAT(visit_date, "%b %Y") as month_label')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('MIN(visit_date) as sort_date')
            ->groupBy('month_label')
            ->orderBy('sort_date')
            ->get()
            ->pluck('count', 'month_label');

        $labels = []; $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $label = now()->subMonths($i)->format('M Y');
            $labels[] = $label;
            $data[] = $trends->get($label, 0);
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
