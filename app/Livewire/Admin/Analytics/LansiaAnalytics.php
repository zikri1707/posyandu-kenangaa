<?php

namespace App\Livewire\Admin\Analytics;

use Livewire\Component;
use Livewire\Attributes\Reactive;
use App\Livewire\Traits\HasPosyanduScope;

class LansiaAnalytics extends Component
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

    // AL-01: Kategori Umur
    #[\Livewire\Attributes\Computed]
    public function ageCategories()
    {
        $patients = $this->applyPosyanduScope(\App\Models\Patient::query(), $this->selectedPosyandu)
            ->where('category', 'lansia')->where('status_mutasi', 'aktif')->get();
        $pra = 0; $lansia = 0; $resti = 0;
        foreach ($patients as $p) {
            if ($p->birth_date) {
                $age = $p->birth_date->age;
                if ($age >= 45 && $age <= 59) $pra++;
                elseif ($age >= 60 && $age <= 69) $lansia++;
                elseif ($age >= 70) $resti++;
            }
        }
        return ['pra' => $pra, 'lansia' => $lansia, 'resti' => $resti];
    }

    // AL-02: IMT
    #[\Livewire\Attributes\Computed]
    public function imtStats()
    {
        $records = $this->applyPosyanduScope(\App\Models\MedicalRecord::query(), $this->selectedPosyandu)
            ->whereHas('patient', function($q) {
                $q->where('category', 'lansia')->where('status_mutasi', 'aktif');
            })
            ->whereYear('visit_date', $this->selectedYear)
            ->when($this->selectedMonth, fn($q) => $q->whereMonth('visit_date', $this->selectedMonth))
            ->orderBy('visit_date', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->unique('patient_id');

        $kurang = 0; $normal = 0; $lebih = 0; $obesitas = 0;
        foreach ($records as $r) {
            if ($r->weight && $r->height) {
                $imt = $r->weight / (($r->height / 100) ** 2);
                if ($imt < 18.5) $kurang++;
                elseif ($imt < 25) $normal++;
                elseif ($imt < 27) $lebih++;
                else $obesitas++;
            }
        }
        return ['kurang' => $kurang, 'normal' => $normal, 'lebih' => $lebih, 'obesitas' => $obesitas];
    }

    // AL-03 to AL-06: Metabolic Risks
    #[\Livewire\Attributes\Computed]
    public function metabolicRisks()
    {
        $records = $this->applyPosyanduScope(\App\Models\MedicalRecord::query(), $this->selectedPosyandu)
            ->whereHas('patient', function($q) {
                $q->where('category', 'lansia')->where('status_mutasi', 'aktif');
            })
            ->whereYear('visit_date', $this->selectedYear)
            ->when($this->selectedMonth, fn($q) => $q->whereMonth('visit_date', $this->selectedMonth))
            ->orderBy('visit_date', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->unique('patient_id');

        $hipertensi = 0; $gula = 0; $kolesterol = 0; $asamUrat = 0;
        foreach ($records as $r) {
            if ($r->systolic_bp >= 140 || $r->diastolic_bp >= 90) $hipertensi++;
            if ($r->blood_sugar >= 200) $gula++;
            if ($r->cholesterol >= 200) $kolesterol++;
            if ($r->uric_acid >= 7.0) $asamUrat++;
        }
        return ['hipertensi' => $hipertensi, 'gula' => $gula, 'kolesterol' => $kolesterol, 'asamUrat' => $asamUrat];
    }

    public function render()
    {
        return view('livewire.admin.analytics.lansia-analytics', [
            'ageCategories' => $this->ageCategories(),
            'imtStats' => $this->imtStats(),
            'metabolicRisks' => $this->metabolicRisks()
        ]);
    }
}

