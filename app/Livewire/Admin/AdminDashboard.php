<?php

namespace App\Livewire\Admin;

use App\Livewire\Shared\BaseAdminComponent;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Posyandu;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AdminDashboard extends BaseAdminComponent
{
    // Filter Properties
    public string $filterPeriode = 'semua';

    public ?string $filterCustomStartDate = null;

    public ?string $filterCustomEndDate = null;

    public string $filterPosyandu = 'semua';

    public string $filterRisiko = 'semua';

    public $availablePosyandus = [];

    // Stats Properties
    public $totalBalita = 0;

    public $totalPemeriksaan = 0;

    public $totalImunisasi = 0;

    public $kunjunganBaru = 0;

    public $balitaStunting = [];

    public array $nutritionStatusDistribution = [];

    public array $monthlyWeighingData = [];

    public $upcomingSchedule = [];

    public $recentActivities = [];

    public $missingImmunizations = [];

    public $bumilRisikoTinggi = [];

    public $recentImmunizations = [];

    // Dashboard metrics
    public $lansiaDemografi = ['60_69' => 0, '70_plus' => 0];

    public $bumilTrimester = ['T1' => 0, 'T2' => 0, 'T3' => 0];

    public $kehadiranBalita = ['hadir' => 0, 'tidak_hadir' => 0, 'persentase' => 0];

    public $kelahiranBulanIni = 0;

    protected $listeners = [
        'refreshDashboard' => 'loadStatistics',
    ];

    public function mount()
    {
        $this->availablePosyandus = Posyandu::all();
        $this->loadStatistics();
    }

    public function updatedFilterPeriode()
    {
        $this->loadStatistics();
    }

    public function updatedFilterCustomStartDate()
    {
        $this->loadStatistics();
    }

    public function updatedFilterCustomEndDate()
    {
        $this->loadStatistics();
    }

    public function updatedFilterPosyandu()
    {
        $this->loadStatistics();
    }

    public function updatedFilterRisiko()
    {
        $this->loadStatistics();
    }

    public function resetFilters()
    {
        $this->filterPeriode = 'semua';
        $this->filterCustomStartDate = null;
        $this->filterCustomEndDate = null;
        $this->filterPosyandu = 'semua';
        $this->filterRisiko = 'semua';
        $this->loadStatistics();
    }

    public function applyDashboardFilters(Builder $query, $type = 'patient')
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            if ($this->filterPosyandu !== 'semua') {
                if ($type === 'patient' || $type === 'schedule') {
                    $query->where('posyandu_id', $this->filterPosyandu);
                } elseif ($type === 'medical_record') {
                    $query->whereHas('patient', function ($q) {
                        $q->where('posyandu_id', $this->filterPosyandu);
                    });
                }
            }
        } else {
            if ($type === 'patient' || $type === 'schedule') {
                $query->where('posyandu_id', Auth::user()->posyandu_id);
            } elseif ($type === 'medical_record') {
                $query->whereHas('patient', function ($q) {
                    $q->where('posyandu_id', Auth::user()->posyandu_id);
                });
            }
        }

        // 2. Date Filtering
        if ($type === 'medical_record') {
            if ($this->filterPeriode === 'bulan_ini') {
                $query->whereMonth('visit_date', now()->month)->whereYear('visit_date', now()->year);
            } elseif ($this->filterPeriode === 'bulan_lalu') {
                $query->whereMonth('visit_date', now()->subMonth()->month)->whereYear('visit_date', now()->subMonth()->year);
            } elseif ($this->filterPeriode === 'tahun_ini') {
                $query->whereYear('visit_date', now()->year);
            } elseif ($this->filterPeriode === 'tahun_lalu') {
                $query->whereYear('visit_date', now()->subYear()->year);
            } elseif ($this->filterPeriode === 'custom' && $this->filterCustomStartDate && $this->filterCustomEndDate) {
                $query->whereBetween('visit_date', [$this->filterCustomStartDate, $this->filterCustomEndDate]);
            }
        }

        return $query;
    }

    public function loadStatistics()
    {
        $hasCustomFilters = ($this->filterPeriode !== 'semua') ||
                            ($this->filterPosyandu !== 'semua') ||
                            ($this->filterRisiko !== 'semua');

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $posyanduId = $user->isSuperAdmin() ? null : $user->posyandu_id;
        $year = now()->year;
        $key = "year_{$year}";

        if (! $hasCustomFilters) {
            $snapshot = \App\Models\AnalyticsSnapshot::where('posyandu_id', $posyanduId)->where('key', $key)->first();
            if ($snapshot && isset($snapshot->data['dashboard_stats']['lansiaDemografi'])) {
                $data = $snapshot->data['dashboard_stats'];
                foreach ($data as $prop => $val) {
                    if (property_exists($this, $prop)) {
                        $this->{$prop} = $val;
                    }
                }
            } else {
                $this->computeDashboardStatsRealtime();
                \App\Jobs\ComputeAnalyticsSnapshot::dispatch($posyanduId, $year);
            }
        } else {
            $this->computeDashboardStatsRealtime();
        }

        // Un-snapshotted logic (recent, alerts)
        $scheduleQuery = clone \App\Models\Schedule::query();
        $scheduleQuery = $this->applyDashboardFilters($scheduleQuery, 'schedule');
        $this->upcomingSchedule = $scheduleQuery->where('start_time', '>=', now())->orderBy('start_time')->first();

        $medicalRecordQuery = clone MedicalRecord::query();
        $medicalRecordQuery = $this->applyDashboardFilters($medicalRecordQuery, 'medical_record');
        $this->recentActivities = (clone $medicalRecordQuery)->with(['patient', 'patient.posyandu', 'user'])->latest('visit_date')->limit(5)->get();

        $patientQuery = clone Patient::query();
        $patientQuery = $this->applyDashboardFilters($patientQuery, 'patient');

        // Stunting alerts
        $latestRecordSubquery = MedicalRecord::selectRaw('MAX(id) as id')->groupBy('patient_id');
        $this->balitaStunting = (clone $patientQuery)
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
            ->get();

        $this->missingImmunizations = (clone $patientQuery)
            ->whereIn('category', ['balita', 'bayi', 'baduta'])
            ->with('medicalRecords')
            ->get()
            ->map(function ($patient) {
                $missing = $patient->getMissingVaccines();
                if (empty($missing)) {
                    return null;
                }

                return ['patient' => $patient, 'missing_count' => count($missing), 'next_vaccine' => $missing[0]];
            })->filter()->sortByDesc('missing_count')->take(5);

        $this->recentImmunizations = (clone $medicalRecordQuery)
            ->where(function ($q) {
                $q->whereNotNull('immunization')->where('immunization', '!=', '')->where('immunization', '!=', 'Tidak ada')
                    ->orWhere(function ($sq) {
                        $sq->whereNotNull('vaccine_name')->where('vaccine_name', '!=', '')->where('vaccine_name', '!=', 'Tidak ada');
                    });
            })
            ->with(['patient', 'user'])
            ->latest('visit_date')
            ->limit(5)
            ->get();

        $this->bumilRisikoTinggi = (clone $patientQuery)
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
                    ->where(function ($sq) {
                        $sq->where('birth_date', '>', now()->subYears(20))
                            ->orWhere('birth_date', '<', now()->subYears(35));
                    });
            })
            ->with(['medicalRecords' => fn ($q) => $q->latest('visit_date')->limit(1)])
            ->limit(10)
            ->get();

    }

    protected function computeDashboardStatsRealtime()
    {
        $patientQuery = $this->applyDashboardFilters(Patient::query(), 'patient');
        $medicalRecordQuery = $this->applyDashboardFilters(MedicalRecord::query(), 'medical_record');
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $this->totalBalita = (clone $patientQuery)->whereIn('category', ['balita', 'bayi', 'baduta'])->count();

        if ($this->filterPeriode === 'semua') {
            $this->kunjunganBaru = (clone $medicalRecordQuery)->whereMonth('visit_date', $currentMonth)->whereYear('visit_date', $currentYear)->count();
        } else {
            $this->kunjunganBaru = (clone $medicalRecordQuery)->count();
        }

        $this->totalPemeriksaan = (clone $medicalRecordQuery)->count();

        $this->totalImunisasi = (clone $medicalRecordQuery)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNotNull('immunization')->where('immunization', '!=', '')->where('immunization', '!=', 'Tidak ada');
                })->orWhere(function ($q) {
                    $q->whereNotNull('vaccine_name')->where('vaccine_name', '!=', '')->where('vaccine_name', '!=', 'Tidak ada');
                });
            })->count();

        $latestRecordSubquery = MedicalRecord::selectRaw('MAX(id) as id')->groupBy('patient_id');

        $this->nutritionStatusDistribution = $this->getNutritionStatusDistribution($medicalRecordQuery, $latestRecordSubquery);
        $this->monthlyWeighingData = $this->getMonthlyWeighingData($medicalRecordQuery);
        $this->lansiaDemografi = $this->getLansiaDemografi($patientQuery);
        $this->bumilTrimester = $this->getBumilTrimester($medicalRecordQuery, $latestRecordSubquery);
        $this->kehadiranBalita = $this->getKehadiranBalita($patientQuery, $medicalRecordQuery, $currentMonth, $currentYear);

        $this->kelahiranBulanIni = (clone $patientQuery)
            ->whereIn('category', ['bayi', 'baduta', 'balita'])
            ->whereMonth('birth_date', $currentMonth)
            ->whereYear('birth_date', $currentYear)
            ->count();
    }

    protected function getLansiaDemografi(Builder $patientQuery): array
    {
        $lansia = (clone $patientQuery)->where('category', 'lansia')->get();
        $group60 = 0;
        $group70 = 0;
        foreach ($lansia as $l) {
            if ($l->birth_date) {
                $age = $l->birth_date->age;
                if ($age >= 70) {
                    $group70++;
                } elseif ($age >= 60) {
                    $group60++;
                }
            }
        }

        return ['60_69' => $group60, '70_plus' => $group70];
    }

    protected function getBumilTrimester(Builder $medicalRecordQuery, Builder $latestRecordSubquery): array
    {
        $records = (clone $medicalRecordQuery)
            ->whereIn('id', $latestRecordSubquery)
            ->whereHas('patient', fn ($q) => $q->where('category', 'ibu_hamil'))
            ->get(['gestational_age']);

        $t1 = 0;
        $t2 = 0;
        $t3 = 0;

        foreach ($records as $record) {
            $weeks = (int) filter_var($record->gestational_age, FILTER_SANITIZE_NUMBER_INT);
            if ($weeks > 0) {
                if ($weeks <= 13) {
                    $t1++;
                } elseif ($weeks <= 27) {
                    $t2++;
                } else {
                    $t3++;
                }
            }
        }

        return ['T1' => $t1, 'T2' => $t2, 'T3' => $t3];
    }

    protected function getKehadiranBalita(Builder $patientQuery, Builder $medicalRecordQuery, $currentMonth, $currentYear): array
    {
        $totalBalita = (clone $patientQuery)->whereIn('category', ['balita', 'bayi', 'baduta'])->count();
        $hadirQuery = (clone $medicalRecordQuery)->whereHas('patient', fn ($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))->distinct('patient_id');
        if ($this->filterPeriode === 'semua') {
            $hadirQuery->whereMonth('visit_date', $currentMonth)->whereYear('visit_date', $currentYear);
        }
        $hadir = $hadirQuery->count('patient_id');
        $tidakHadir = max(0, $totalBalita - $hadir);
        $persentase = $totalBalita > 0 ? round(($hadir / $totalBalita) * 100, 1) : 0;

        return ['hadir' => $hadir, 'tidak_hadir' => $tidakHadir, 'persentase' => $persentase];
    }

    protected function getNutritionStatusDistribution(Builder $medicalRecordQuery, Builder $latestRecordSubquery): array
    {
        $distribution = (clone $medicalRecordQuery)->whereIn('id', $latestRecordSubquery)->whereHas('patient', fn ($q) => $q->whereIn('category', ['balita', 'bayi', 'baduta']))->whereNotNull('nutrition_status')->select('nutrition_status', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))->groupBy('nutrition_status')->pluck('total', 'nutrition_status');

        return ['labels' => $distribution->keys()->toArray(), 'data' => $distribution->values()->toArray()];
    }

    protected function getMonthlyWeighingData(Builder $medicalRecordQuery): array
    {
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $dbDriver = $medicalRecordQuery->getConnection()->getDriverName();
        $dateFormat = match ($dbDriver) {
            'sqlite' => 'strftime("%m %Y", visit_date)',
            'pgsql' => 'TO_CHAR(visit_date, \'MM YYYY\')',
            default => 'DATE_FORMAT(visit_date, "%m %Y")',
        };
        $trends = (clone $medicalRecordQuery)->when($this->filterPeriode === 'semua', function ($query) use ($startDate) {
            return $query->where('visit_date', '>=', $startDate);
        })->selectRaw("$dateFormat as month_year")->selectRaw('COUNT(*) as total')->groupByRaw($dateFormat)->get()->pluck('total', 'month_year');
        $labels = [];
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $carbon = now()->subMonths($i);
            $labels[] = $carbon->translatedFormat('M Y');
            $data[] = $trends->get($carbon->format('m Y'), 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard')->layout('layouts.app');
    }
}
