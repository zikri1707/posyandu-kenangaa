<?php

namespace App\Livewire\Admin;

use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Schedule;
use App\Livewire\Shared\BaseAdminComponent;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminDashboard extends BaseAdminComponent
{
    // Properties untuk statistik
    public $totalBalita;
    public $totalIbuHamil;
    public $totalRemaja;
    public $totalLansia;
    public $jadwalAktif;
    public $kunjunganBaru;
    public $balitaStunting;
    public $nutritionStatusDistribution;
    public $monthlyWeighingData;
    public $upcomingSchedule;
    public $recentActivities;
    public $posyanduStats = [];

    public function mount()
    {
        $this->loadStatistics();
    }

    protected function loadStatistics()
    {
        $user = Auth::user();
        $posyanduId = $user->isSuperAdmin() ? null : $user->posyandu_id;
        $year = now()->year;
        $key = "year_{$year}";

        $snapshot = \App\Models\AnalyticsSnapshot::where('posyandu_id', $posyanduId)
            ->where('key', $key)
            ->first();

        if ($snapshot) {
            $data = $snapshot->data['dashboard_stats'];
            foreach ($data as $prop => $val) {
                if (property_exists($this, $prop)) {
                    $this->{$prop} = $val;
                }
            }
        } else {
            // Fallback: load everything in real-time once
            $this->computeDashboardStatsRealtime();
            // Dispatch job for next time
            \App\Jobs\ComputeAnalyticsSnapshot::dispatch($posyanduId, $year);
        }

        // --- Real-time components (un-snapshotted) ---
        $scheduleQuery = $this->applyPosyanduScope(\App\Models\Schedule::query());
        $medicalRecordQuery = $this->applyPosyanduScope(\App\Models\MedicalRecord::query());

        // Balita Stunting Alert (Keep real-time for immediate action)
        $latestRecordSubquery = \App\Models\MedicalRecord::selectRaw('MAX(id) as id')->groupBy('patient_id');
        $this->balitaStunting = $this->applyPosyanduScope(\App\Models\Patient::query())
            ->where('category', 'balita')
            ->whereHas('medicalRecords', function ($query) use ($latestRecordSubquery) {
                $query->whereIn('nutrition_status', [\App\Models\MedicalRecord::NUTRITION_STUNTING, \App\Models\MedicalRecord::NUTRITION_GIZI_BURUK])
                      ->whereIn('id', $latestRecordSubquery);
            })
            ->with(['medicalRecords' => fn($q) => $q->latest('visit_date')->limit(1)])
            ->get();

        $this->upcomingSchedule = $scheduleQuery
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->first();

        $this->recentActivities = $medicalRecordQuery
            ->with(['patient', 'patient.posyandu', 'user'])
            ->latest('visit_date')
            ->latest('created_at')
            ->limit(5)
            ->get();

        if ($user->isSuperAdmin()) {
            $this->posyanduStats = \App\Models\Posyandu::withCount('patients')->get();
        }
    }

    protected function computeDashboardStatsRealtime()
    {
        $patientQuery = $this->applyPosyanduScope(\App\Models\Patient::query());
        $medicalRecordQuery = $this->applyPosyanduScope(\App\Models\MedicalRecord::query());
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $counts = (clone $patientQuery)
            ->selectRaw('COUNT(CASE WHEN category = "balita" THEN 1 END) as balita')
            ->selectRaw('COUNT(CASE WHEN category = "ibu_hamil" THEN 1 END) as ibu_hamil')
            ->selectRaw('COUNT(CASE WHEN category = "remaja" THEN 1 END) as remaja')
            ->selectRaw('COUNT(CASE WHEN category = "lansia" THEN 1 END) as lansia')
            ->first();

        $this->totalBalita = $counts->balita ?? 0;
        $this->totalIbuHamil = $counts->ibu_hamil ?? 0;
        $this->totalRemaja = $counts->remaja ?? 0;
        $this->totalLansia = $counts->lansia ?? 0;

        $this->kunjunganBaru = (clone $medicalRecordQuery)
            ->whereMonth('visit_date', $currentMonth)
            ->whereYear('visit_date', $currentYear)
            ->count();

        $latestRecordSubquery = \App\Models\MedicalRecord::selectRaw('MAX(id) as id')->groupBy('patient_id');
        $this->nutritionStatusDistribution = $this->getNutritionStatusDistribution($medicalRecordQuery, $latestRecordSubquery);
        $this->monthlyWeighingData = $this->getMonthlyWeighingData($medicalRecordQuery);
    }

    protected function getNutritionStatusDistribution($medicalRecordQuery, $latestRecordSubquery)
    {
        $distribution = (clone $medicalRecordQuery)
            ->whereIn('id', $latestRecordSubquery)
            ->whereHas('patient', fn($q) => $q->where('category', 'balita'))
            ->whereNotNull('nutrition_status')
            ->select('nutrition_status', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
            ->groupBy('nutrition_status')
            ->pluck('total', 'nutrition_status');

        return [
            'labels' => $distribution->keys()->toArray(),
            'data' => $distribution->values()->toArray(),
        ];
    }

    protected function getMonthlyWeighingData($medicalRecordQuery)
    {
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        
        $trends = (clone $medicalRecordQuery)
            ->where('visit_date', '>=', $startDate)
            ->select('id', 'visit_date')
            ->get()
            ->groupBy(function ($record) {
                return Carbon::parse($record->visit_date)->format('M Y');
            })
            ->map(function ($group) {
                return $group->count();
            });

        $labels = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $label = Carbon::now()->subMonths($i)->format('M Y');
            $labels[] = $label;
            $data[] = $trends->get($label, 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard');
    }
}
