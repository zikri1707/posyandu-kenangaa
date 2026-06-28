<?php

namespace App\Livewire\Admin\Analytics;

use Livewire\Component;
use Livewire\Attributes\Reactive;
use App\Livewire\Traits\HasPosyanduScope;

class IbuHamilAnalytics extends Component
{
    use HasPosyanduScope;

<<<<<<< HEAD
    #[Reactive]
    public $selectedYear;
=======
    public ?int $selectedYear = null;
    public ?int $selectedMonth = null;
    public ?int $selectedPosyandu = null;
>>>>>>> bc548dd93fee3cc7787407528fa68fa3737e5322

    #[Reactive]
    public $selectedMonth;

    #[Reactive]
    public $selectedPosyandu;

    // AH-01: Validasi Total Ibu Hamil per Trimester
    #[\Livewire\Attributes\Computed]
    public function trimesterStats()
    {
        $records = $this->applyPosyanduScope(\App\Models\MedicalRecord::query(), $this->selectedPosyandu)
            ->whereHas('patient', function($q) {
                $q->where('category', 'ibu_hamil')->where('status_mutasi', 'aktif');
            })
            ->whereYear('visit_date', $this->selectedYear)
            ->when($this->selectedMonth, fn($q) => $q->whereMonth('visit_date', $this->selectedMonth))
            ->orderBy('visit_date', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->unique('patient_id');

        $t1 = 0; $t2 = 0; $t3 = 0;
        foreach ($records as $record) {
            $weeks = (int) filter_var($record->gestational_age, FILTER_SANITIZE_NUMBER_INT);
            if ($weeks > 0 && $weeks <= 13) $t1++;
            elseif ($weeks > 13 && $weeks <= 27) $t2++;
            elseif ($weeks > 27) $t3++;
        }

        return ['T1' => $t1, 'T2' => $t2, 'T3' => $t3];
    }

    // AH-02 & AH-03: HPL & Risiko 4T
    #[\Livewire\Attributes\Computed]
    public function riskStats()
    {
        $patients = $this->applyPosyanduScope(\App\Models\Patient::query(), $this->selectedPosyandu)
            ->where('category', 'ibu_hamil')
            ->where('status_mutasi', 'aktif')
            ->with(['medicalRecords' => function($query) {
                $query->whereYear('visit_date', $this->selectedYear)
                      ->when($this->selectedMonth, fn($q) => $q->whereMonth('visit_date', $this->selectedMonth));
            }])
            ->get();
        $highRisk = 0;
        $normal = 0;

        foreach ($patients as $p) {
            $isHighRisk = false;
            if ($p->birth_date) {
                $age = $p->birth_date->age;
                if ($age < 20 || $age > 35) $isHighRisk = true;
            }
            
            $latestRecord = $p->medicalRecords->sortByDesc('visit_date')->first();
            if ($latestRecord && $latestRecord->height && $latestRecord->height < 145) {
                $isHighRisk = true;
            }

            if ($isHighRisk) $highRisk++;
            else $normal++;
        }

        return ['highRisk' => $highRisk, 'normal' => $normal];
    }

    // AH-06: Anemia
    #[\Livewire\Attributes\Computed]
    public function anemiaStats()
    {
        $records = $this->applyPosyanduScope(\App\Models\MedicalRecord::query(), $this->selectedPosyandu)
            ->whereHas('patient', function($q) {
                $q->where('category', 'ibu_hamil')->where('status_mutasi', 'aktif');
            })
            ->whereYear('visit_date', $this->selectedYear)
            ->when($this->selectedMonth, fn($q) => $q->whereMonth('visit_date', $this->selectedMonth))
            ->orderBy('visit_date', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->unique('patient_id');

        return $records->whereNotNull('hemoglobin')->where('hemoglobin', '<', 11)->count();
    }

    // AH-04: TTD Status
    #[\Livewire\Attributes\Computed]
    public function ttdStats()
    {
        $records = $this->applyPosyanduScope(\App\Models\MedicalRecord::query(), $this->selectedPosyandu)
            ->whereHas('patient', function($q) {
                $q->where('category', 'ibu_hamil')->where('status_mutasi', 'aktif');
            })
            ->whereYear('visit_date', $this->selectedYear)
            ->when($this->selectedMonth, fn($q) => $q->whereMonth('visit_date', $this->selectedMonth))
            ->orderBy('visit_date', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->unique('patient_id');

        $received = $records->where('nakes_gives_fe_mms', 1)->count();
        $notReceived = $records->where('nakes_gives_fe_mms', 0)->count();

        return ['received' => $received, 'notReceived' => $notReceived];
    }

    // AH-05: K1-K6 Kunjungan
    #[\Livewire\Attributes\Computed]
    public function ancStats()
    {
        $patients = $this->applyPosyanduScope(\App\Models\Patient::query(), $this->selectedPosyandu)
            ->where('category', 'ibu_hamil')
            ->where('status_mutasi', 'aktif')
            ->with(['medicalRecords' => function($query) {
                $query->whereYear('visit_date', $this->selectedYear)
                      ->when($this->selectedMonth, fn($q) => $q->whereMonth('visit_date', $this->selectedMonth));
            }])
            ->get();

        $k1 = 0; $k2 = 0; $k3 = 0; $k4 = 0; $k5 = 0; $k6 = 0;

        foreach ($patients as $p) {
            $visitCount = $p->medicalRecords->count();
            if ($visitCount >= 1) $k1++;
            if ($visitCount >= 2) $k2++;
            if ($visitCount >= 3) $k3++;
            if ($visitCount >= 4) $k4++;
            if ($visitCount >= 5) $k5++;
            if ($visitCount >= 6) $k6++;
        }

        return ['k1' => $k1, 'k2' => $k2, 'k3' => $k3, 'k4' => $k4, 'k5' => $k5, 'k6' => $k6];
    }

    public function render()
    {
        return view('livewire.admin.analytics.ibu-hamil-analytics', [
            'trimesterStats' => $this->trimesterStats(),
            'riskStats' => $this->riskStats(),
            'anemiaCount' => $this->anemiaStats(),
            'ttdStats' => $this->ttdStats(),
            'ancStats' => $this->ancStats()
        ]);
    }
}

