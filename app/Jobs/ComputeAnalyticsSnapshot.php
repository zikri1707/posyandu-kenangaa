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

        $year = $this->year;
        $month = $this->month;

        // Determination date for age calculation
        $determinationDate = $month
            ? Carbon::create($year, $month)->endOfMonth()
            : Carbon::create($year)->endOfYear();

        $basePatientFilter = fn ($q) => $q->whereYear('visit_date', $year)
            ->when($month, fn ($mq) => $mq->whereMonth('visit_date', $month));

        // ── 1. GLOBAL OVERVIEW STATS ────────────────────────────────
        $totalBalita = (clone $patientQuery)
            ->whereIn('category', ['balita', 'bayi', 'baduta'])
            ->count();

        $totalIbuHamil = (clone $patientQuery)
            ->where('category', 'ibu_hamil')
            ->count();

        $totalLansia = (clone $patientQuery)
            ->where('category', 'lansia')
            ->count();

        $totalKunjungan = (clone $medicalRecordQuery)
            ->whereYear('visit_date', $year)
            ->when($month, fn ($q) => $q->whereMonth('visit_date', $month))
            ->count();

        $kaderAktif = User::where('is_active', true)
            ->whereIn('role', ['staff', 'medical', 'admin', 'kader'])
            ->when($this->posyanduId, fn ($q) => $q->where('posyandu_id', $this->posyanduId))
            ->count();

        // Combined Monthly Visits Trend (12 Months)
        $recordsYear = (clone $medicalRecordQuery)
            ->with('patient')
            ->whereYear('visit_date', $year)
            ->get();

        $trendLabels = [];
        $trendVisitsBalita = [];
        $trendVisitsIbuHamil = [];
        $trendVisitsLansia = [];

        for ($m = 1; $m <= 12; $m++) {
            $trendLabels[] = Carbon::create($year, $m)->translatedFormat('M');
            $monthRecords = $recordsYear->filter(fn ($r) => Carbon::parse($r->visit_date)->month === $m);
            $trendVisitsBalita[] = $monthRecords->filter(fn ($r) => $r->patient && in_array($r->patient->category, ['balita', 'bayi', 'baduta']))->count();
            $trendVisitsIbuHamil[] = $monthRecords->filter(fn ($r) => $r->patient && $r->patient->category === 'ibu_hamil')->count();
            $trendVisitsLansia[] = $monthRecords->filter(fn ($r) => $r->patient && $r->patient->category === 'lansia')->count();
        }

        // ── 2. BALITA ANALYTICS ─────────────────────────────────────
        $latestRecordSubquery = MedicalRecord::selectRaw('MAX(id) as id')
            ->whereYear('visit_date', $year)
            ->when($month, fn ($q) => $q->whereMonth('visit_date', $month))
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
            })->whereYear('visit_date', $year)
                ->when($month, fn ($mq) => $mq->whereMonth('visit_date', $month))
                ->whereIn('id', $latestRecordSubquery)
            )
            ->count();

        $stuntingRate = $totalWithRecord > 0 ? round(($stuntingCount / $totalWithRecord) * 100, 1) : 0;

        $balitaWithImunisasi = (clone $medicalRecordQuery)
            ->whereHas('patient', fn ($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNotNull('immunization')
                        ->where('immunization', '!=', '')
                        ->where('immunization', '!=', 'Tidak ada');
                })->orWhere(function ($q) {
                    $q->whereNotNull('vaccine_name')
                        ->where('vaccine_name', '!=', '')
                        ->where('vaccine_name', '!=', 'Tidak ada');
                });
            })
            ->whereYear('visit_date', $year)
            ->when($month, fn ($q) => $q->whereMonth('visit_date', $month))
            ->distinct('patient_id')
            ->count('patient_id');

        $cakupanImunisasi = $totalBalita > 0 ? round(($balitaWithImunisasi / $totalBalita) * 100, 1) : 0;

        $balitaRecords = (clone $medicalRecordQuery)
            ->whereHas('patient', fn ($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))
            ->whereYear('visit_date', $year)
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
            ->whereYear('visit_date', $year)
            ->when($month, fn ($q) => $q->whereMonth('visit_date', $month))
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

        $stuntingByPosyandu = [];
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
            ->whereHas('medicalRecords', fn ($q) => $q->where(function ($sq) {
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

        // Vaccine types counts
        $vaccineList = ['HB-0', 'BCG', 'Polio 1', 'Polio 2', 'Polio 3', 'Polio 4', 'DPT-HB-Hib 1', 'DPT-HB-Hib 2', 'DPT-HB-Hib 3', 'PCV 1', 'PCV 2', 'PCV 3', 'RV 1', 'RV 2', 'RV 3', 'IPV 1', 'IPV 2', 'MR'];
        $recordsForVaccines = (clone $medicalRecordQuery)
            ->whereHas('patient', fn ($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))
            ->whereYear('visit_date', $year)
            ->when($month, fn ($q) => $q->whereMonth('visit_date', $month))
            ->whereNotNull('vaccine_name')
            ->where('vaccine_name', '!=', '')
            ->get(['patient_id', 'vaccine_name']);

        $vaccineCounts = [];
        foreach ($vaccineList as $vaxName) {
            $vaccineCounts[$vaxName] = $recordsForVaccines
                ->filter(fn ($r) => stripos($r->vaccine_name, $vaxName) !== false)
                ->pluck('patient_id')
                ->unique()
                ->count();
        }
        $vaccineLabels = array_keys($vaccineCounts);
        $vaccineData = array_values($vaccineCounts);

        // ── 3. PREGNANCY (IBU HAMIL) ANALYTICS ─────────────────────
        $latestRecordSubqueryPreg = MedicalRecord::selectRaw('MAX(id) as id')
            ->whereYear('visit_date', $year)
            ->when($month, fn ($q) => $q->whereMonth('visit_date', $month))
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

        $pregRecords = (clone $medicalRecordQuery)
            ->whereHas('patient', fn ($q) => $q->where('category', 'ibu_hamil'))
            ->whereYear('visit_date', $year)
            ->get(['id', 'patient_id', 'visit_date', 'systolic_bp', 'diastolic_bp', 'pill_fe']);

        $trendPregnancyHypertension = [];
        $trendPregnancyFe = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthRecs = $pregRecords->filter(fn ($r) => Carbon::parse($r->visit_date)->month === $m);
            $latestRecordsByPatient = $monthRecs->sortByDesc('id')->unique('patient_id');
            $totalM = $latestRecordsByPatient->count();

            if ($totalM > 0) {
                $hyperM = $latestRecordsByPatient->filter(fn ($r) => $r->systolic_bp >= 140 || $r->diastolic_bp >= 90)->count();
                $feM = $latestRecordsByPatient->filter(fn ($r) => $r->pill_fe == 1)->count();
                $trendPregnancyHypertension[] = round(($hyperM / $totalM) * 100, 1);
                $trendPregnancyFe[] = round(($feM / $totalM) * 100, 1);
            } else {
                $trendPregnancyHypertension[] = 0;
                $trendPregnancyFe[] = 0;
            }
        }

        // ── 4. LANSIA ANALYTICS ────────────────────────────────────
        $latestRecordSubqueryLansia = MedicalRecord::selectRaw('MAX(id) as id')
            ->whereYear('visit_date', $year)
            ->when($month, fn ($q) => $q->whereMonth('visit_date', $month))
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

        $lansiaRecords = (clone $medicalRecordQuery)
            ->whereHas('patient', fn ($q) => $q->where('category', 'lansia'))
            ->whereYear('visit_date', $year)
            ->get(['id', 'patient_id', 'visit_date', 'systolic_bp', 'diastolic_bp', 'blood_sugar', 'cholesterol', 'uric_acid']);

        for ($m = 1; $m <= 12; $m++) {
            $monthRecs = $lansiaRecords->filter(fn ($r) => Carbon::parse($r->visit_date)->month === $m);
            $latestRecordsByPatient = $monthRecs->sortByDesc('id')->unique('patient_id');
            $totalLM = $latestRecordsByPatient->count();

            if ($totalLM > 0) {
                $hyperLM = $latestRecordsByPatient->filter(fn ($r) => $r->systolic_bp >= 140 || $r->diastolic_bp >= 90)->count();
                $sugarLM = $latestRecordsByPatient->filter(fn ($r) => $r->blood_sugar >= 200)->count();
                $cholLM = $latestRecordsByPatient->filter(fn ($r) => $r->cholesterol >= 200)->count();
                $uricLM = $latestRecordsByPatient->filter(fn ($r) => $r->uric_acid >= 7.0)->count();

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

        $counts = (object) [
            'balita' => (clone $patientQuery)->whereIn('category', ['balita', 'bayi', 'baduta'])->count(),
            'ibu_hamil' => (clone $patientQuery)->where('category', 'ibu_hamil')->count(),
            'remaja' => (clone $patientQuery)->where('category', 'remaja')->count(),
            'lansia' => (clone $patientQuery)->where('category', 'lansia')->count(),
        ];

        $kunjunganBaru = (clone $medicalRecordQuery)
            ->whereMonth('visit_date', $currentMonth)
            ->whereYear('visit_date', $currentYear)
            ->count();

        $totalPemeriksaan = (clone $medicalRecordQuery)->count();

        $totalImunisasi = (clone $medicalRecordQuery)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNotNull('immunization')
                        ->where('immunization', '!=', '')
                        ->where('immunization', '!=', 'Tidak ada');
                })->orWhere(function ($q) {
                    $q->whereNotNull('vaccine_name')
                        ->where('vaccine_name', '!=', '')
                        ->where('vaccine_name', '!=', 'Tidak ada');
                });
            })
            ->count();

        $latestRecordSubquery = MedicalRecord::selectRaw('MAX(id) as id')->groupBy('patient_id');

        $distribution = (clone $medicalRecordQuery)
            ->whereIn('id', $latestRecordSubquery)
            ->whereHas('patient', fn ($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))
            ->whereNotNull('nutrition_status')
            ->select('nutrition_status', DB::raw('COUNT(*) as total'))
            ->groupBy('nutrition_status')
            ->pluck('total', 'nutrition_status');

        $startDate = now()->subMonths(11)->startOfMonth();

        $dbDriver = $medicalRecordQuery->getConnection()->getDriverName();
        $dateFormat = match ($dbDriver) {
            'sqlite' => "strftime('%m %Y', visit_date)",
            'pgsql' => "TO_CHAR(visit_date, 'MM YYYY')",
            default => "DATE_FORMAT(visit_date, '%m %Y')",
        };

        $trends = (clone $medicalRecordQuery)
            ->where('visit_date', '>=', $startDate)
            ->selectRaw("$dateFormat as month_year")
            ->selectRaw('COUNT(*) as total')
            ->groupByRaw($dateFormat)
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

        // ── DASH-04: Lansia Demografi ──
        $lansiaList = (clone $patientQuery)->where('category', 'lansia')->get(['birth_date']);
        $lansiaDemografi = ['60_69' => 0, '70_plus' => 0];
        foreach ($lansiaList as $l) {
            $age = $l->birth_date ? Carbon::parse($l->birth_date)->age : 0;
            if ($age >= 60 && $age < 70) {
                $lansiaDemografi['60_69']++;
            } elseif ($age >= 70) {
                $lansiaDemografi['70_plus']++;
            }
        }

        // ── DASH-02: Bumil Trimester ──
        $bumilRecords = (clone $medicalRecordQuery)
            ->whereIn('id', $latestRecordSubquery)
            ->whereHas('patient', fn ($q) => $q->where('category', 'ibu_hamil'))
            ->get(['gestational_age']);
        $bumilTrimester = ['T1' => 0, 'T2' => 0, 'T3' => 0];
        foreach ($bumilRecords as $record) {
            $weeks = (int) filter_var($record->gestational_age, FILTER_SANITIZE_NUMBER_INT);
            if ($weeks > 0) {
                if ($weeks <= 13) {
                    $bumilTrimester['T1']++;
                } elseif ($weeks <= 27) {
                    $bumilTrimester['T2']++;
                } else {
                    $bumilTrimester['T3']++;
                }
            }
        }

        // ── DASH-06: Kehadiran Balita ──
        $totalBalitaForHadir = (clone $patientQuery)->whereIn('category', ['balita', 'bayi', 'baduta'])->count();
        $hadir = (clone $medicalRecordQuery)
            ->whereHas('patient', fn ($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))
            ->whereMonth('visit_date', $currentMonth)
            ->whereYear('visit_date', $currentYear)
            ->distinct('patient_id')
            ->count('patient_id');
        $tidakHadir = max(0, $totalBalitaForHadir - $hadir);
        $persentaseHadir = $totalBalitaForHadir > 0 ? round(($hadir / $totalBalitaForHadir) * 100, 1) : 0;
        $kehadiranBalita = [
            'hadir' => $hadir,
            'tidak_hadir' => $tidakHadir,
            'persentase' => $persentaseHadir,
        ];

        // ── DASH-07: Kelahiran Bulan Ini ──
        $kelahiranBulanIni = (clone $patientQuery)
            ->whereIn('category', ['balita', 'bayi', 'baduta'])
            ->whereMonth('birth_date', $currentMonth)
            ->whereYear('birth_date', $currentYear)
            ->count();

        // ── Cache additional heavy widgets in the background job ──
        $recentActivities = (clone $medicalRecordQuery)
            ->with(['patient', 'patient.posyandu', 'user'])
            ->latest('visit_date')
            ->limit(5)
            ->get()
            ->toArray();

        $balitaStunting = (clone $patientQuery)
            ->whereIn('category', ['balita', 'bayi', 'baduta'])
            ->whereHas('medicalRecords', function ($query) use ($latestRecordSubquery) {
                $query->whereIn('id', $latestRecordSubquery)
                    ->where(function ($sq) {
                        $sq->whereIn('nutrition_status', ['Berat Badan Kurang', 'Berat Badan Sangat Kurang', 'Gizi Kurang', 'Gizi Buruk'])
                            ->orWhereIn('stunting_status', ['Pendek', 'Sangat Pendek'])
                            ->orWhereIn('wasting_status', ['Gizi Kurang', 'Gizi Buruk']);
                    });
            })
            ->with(['medicalRecords' => fn ($q) => $q->latest('visit_date')->limit(1)])
            ->limit(10)
            ->get()
            ->toArray();

        $missingImmunizations = (clone $patientQuery)
            ->whereIn('category', ['balita', 'bayi', 'baduta'])
            ->where('status_mutasi', 'aktif')
            ->with('medicalRecords')
            ->get()
            ->map(function ($patient) {
                $missing = $patient->getMissingVaccines();
                if (empty($missing)) {
                    return null;
                }

                return [
                    'patient' => $patient->toArray(),
                    'missing_count' => count($missing),
                    'next_vaccine' => $missing[0]
                ];
            })
            ->filter()
            ->sortByDesc('missing_count')
            ->take(5)
            ->values()
            ->toArray();

        $recentImmunizations = (clone $medicalRecordQuery)
            ->where(function ($q) {
                $q->whereNotNull('immunization')->where('immunization', '!=', '')->where('immunization', '!=', 'Tidak ada')
                    ->orWhere(function ($sq) {
                        $sq->whereNotNull('vaccine_name')->where('vaccine_name', '!=', '')->where('vaccine_name', '!=', 'Tidak ada');
                    });
            })
            ->with(['patient', 'user'])
            ->latest('visit_date')
            ->limit(5)
            ->get()
            ->toArray();

        $bumilRisikoTinggi = (clone $patientQuery)
            ->where('category', 'ibu_hamil')
            ->whereHas('medicalRecords', function ($query) use ($latestRecordSubquery) {
                $query->whereIn('id', $latestRecordSubquery)
                    ->where(function ($sq) {
                        $sq->where('upper_arm_circumference', '<', 23.5)
                            ->orWhere('systolic_bp', '>=', 140)
                            ->orWhere('diastolic_bp', '>=', 90);
                    });
            })
            ->orWhere(function ($query) {
                $query->where('category', 'ibu_hamil')
                    ->when($this->posyanduId, fn ($q) => $q->where('posyandu_id', $this->posyanduId))
                    ->where(function ($sq) {
                        $sq->where('birth_date', '>', now()->subYears(20))
                            ->orWhere('birth_date', '<', now()->subYears(35));
                    });
            })
            ->with(['medicalRecords' => fn ($q) => $q->latest('visit_date')->limit(1)])
            ->limit(10)
            ->get()
            ->toArray();

        // Lansia Names
        $lansia = (clone $patientQuery)->where('category', 'lansia')->get(['id', 'full_name', 'birth_date']);
        $group60 = [];
        $group70 = [];
        foreach ($lansia as $l) {
            if ($l->birth_date) {
                $age = $l->birth_date->age;
                $lansiaData = [
                    'id' => $l->id,
                    'name' => $l->full_name,
                    'age' => $age,
                ];
                if ($age >= 70) {
                    $group70[] = $lansiaData;
                } elseif ($age >= 60) {
                    $group60[] = $lansiaData;
                }
            }
        }
        $lansiaDemografiNames = ['60_69' => $group60, '70_plus' => $group70];

        // Bumil Names
        $records = (clone $medicalRecordQuery)
            ->whereIn('id', $latestRecordSubquery)
            ->whereHas('patient', fn ($q) => $q->where('category', 'ibu_hamil'))
            ->with(['patient:id,full_name'])
            ->get(['id', 'patient_id', 'gestational_age']);

        $t1 = [];
        $t2 = [];
        $t3 = [];

        foreach ($records as $record) {
            $weeks = (int) filter_var($record->gestational_age, FILTER_SANITIZE_NUMBER_INT);
            if ($weeks > 0 && $record->patient) {
                $patientData = [
                    'id' => $record->patient->id,
                    'name' => $record->patient->full_name,
                    'gestational_age' => $record->gestational_age,
                ];
                if ($weeks <= 13) {
                    $t1[] = $patientData;
                } elseif ($weeks <= 27) {
                    $t2[] = $patientData;
                } else {
                    $t3[] = $patientData;
                }
            }
        }
        $bumilTrimesterNames = ['T1' => $t1, 'T2' => $t2, 'T3' => $t3];

        return [
            'totalBalita' => $counts->balita ?? 0,
            'totalIbuHamil' => $counts->ibu_hamil ?? 0,
            'totalRemaja' => $counts->remaja ?? 0,
            'totalLansia' => $counts->lansia ?? 0,
            'kunjunganBaru' => $kunjunganBaru,
            'totalPemeriksaan' => $totalPemeriksaan,
            'totalImunisasi' => $totalImunisasi,
            'nutritionStatusDistribution' => [
                'labels' => $distribution->keys()->toArray(),
                'data' => $distribution->values()->toArray(),
            ],
            'monthlyWeighingData' => ['labels' => $labels, 'data' => $data],
            // New Dashboard Widgets
            'lansiaDemografi' => $lansiaDemografi,
            'bumilTrimester' => $bumilTrimester,
            'kehadiranBalita' => $kehadiranBalita,
            'kelahiranBulanIni' => $kelahiranBulanIni,

            // Heavy widgets cached
            'recentActivities' => $recentActivities,
            'balitaStunting' => $balitaStunting,
            'missingImmunizations' => $missingImmunizations,
            'recentImmunizations' => $recentImmunizations,
            'bumilRisikoTinggi' => $bumilRisikoTinggi,
            'lansiaDemografiNames' => $lansiaDemografiNames,
            'bumilTrimesterNames' => $bumilTrimesterNames,
        ];
    }
}
