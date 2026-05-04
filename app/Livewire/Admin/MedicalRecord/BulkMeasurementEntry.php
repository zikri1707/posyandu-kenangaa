<?php

namespace App\Livewire\Admin\MedicalRecord;

use Livewire\Component;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Posyandu;
use App\Services\NutritionCalculatorService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BulkMeasurementEntry extends Component
{
    public $posyandu_id;
    public $visit_date;
    public $measurements = [];
    public $search = '';
    public $searchResults = [];

    protected $rules = [
        'posyandu_id' => 'required|exists:posyandus,id',
        'visit_date' => 'required|date',
        'measurements.*.weight' => 'nullable|numeric|min:0.1|max:50',
        'measurements.*.height' => 'nullable|numeric|min:30|max:150',
        'measurements.*.measurement_method' => 'required|in:Berdiri,Terlentang',
    ];

    public function mount()
    {
        $this->visit_date = now()->format('Y-m-d');
        
        // If user is a Kader, set their posyandu_id
        if (Auth::user()->posyandu_id) {
            $this->posyandu_id = Auth::user()->posyandu_id;
        }
    }

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->searchResults = [];
            return;
        }

        $query = Patient::query();
        
        if ($this->posyandu_id) {
            $query->where('posyandu_id', $this->posyandu_id);
        }

        $this->searchResults = $query->where(function($q) {
                $q->where('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('id_number', 'like', '%' . $this->search . '%');
            })
            ->limit(5)
            ->get();
    }

    public function addPatient($id)
    {
        // Check if already in list
        if (collect($this->measurements)->contains('patient_id', $id)) {
            $this->search = '';
            $this->searchResults = [];
            return;
        }

        $patient = Patient::find($id);
        if ($patient) {
            $lastRecord = $patient->medicalRecords()->latest()->first();
            $this->measurements[] = [
                'patient_id' => $patient->id,
                'full_name' => $patient->full_name,
                'parent_name' => $patient->parent_name,
                'age_months' => $patient->age_in_months,
                'gender' => $patient->gender,
                'last_weight' => $lastRecord?->weight ?? '-',
                'last_height' => $lastRecord?->height ?? '-',
                'weight' => '',
                'height' => '',
                'measurement_method' => $patient->age_in_months >= 24 ? 'Berdiri' : 'Terlentang',
            ];
        }

        $this->search = '';
        $this->searchResults = [];
    }

    public function removePatient($index)
    {
        unset($this->measurements[$index]);
        $this->measurements = array_values($this->measurements);
    }

    public function updatedMeasurements($value, $key)
    {
        // Format key: "index.field" (contoh: "0.weight")
        $parts = explode('.', $key);
        if (count($parts) < 2) return;

        $index = $parts[0];
        $field = $parts[1];

        if ($field === 'weight' || $field === 'height') {
            $m = $this->measurements[$index];
            
            // Hitung hanya jika kedua data ada (BB/TB butuh keduanya, BB/U butuh BB)
            if (!empty($m['weight'])) {
                $nutritionService = new NutritionCalculatorService();
                
                // Kalkulasi BB/U
                $zWfa = $nutritionService->calculateWeightForAge(
                    (float)$m['weight'], 
                    (int)$m['age_months'], 
                    $m['gender']
                );
                $this->measurements[$index]['status_bbu'] = $nutritionService->classifyNutritionStatus($zWfa);

                // Kalkulasi BB/TB (Jika ada TB)
                if (!empty($m['height'])) {
                    $zWfh = $nutritionService->calculateWeightForHeight(
                        (float)$m['weight'], 
                        (float)$m['height'], 
                        $m['gender']
                    );
                    $this->measurements[$index]['status_bbtb'] = $nutritionService->classifyWastingStatus($zWfh);
                } else {
                    $this->measurements[$index]['status_bbtb'] = null;
                }
            } else {
                $this->measurements[$index]['status_bbu'] = null;
                $this->measurements[$index]['status_bbtb'] = null;
            }
        }
    }

    public function save()
    {
        $this->validate();

        $count = 0;
        $nutritionService = new NutritionCalculatorService();

        foreach ($this->measurements as $m) {
            if (empty($m['weight']) || empty($m['height'])) continue;

            // Check for existing record on same date to avoid duplication
            $existing = MedicalRecord::where('patient_id', $m['patient_id'])
                ->whereDate('visit_date', $this->visit_date)
                ->first();

            if ($existing) continue;

            $results = $nutritionService->calculateAll(
                (float)$m['weight'],
                (float)$m['height'],
                (int)$m['age_months'],
                $m['gender']
            );

            MedicalRecord::create([
                'patient_id' => $m['patient_id'],
                'user_id' => Auth::id(),
                'visit_date' => $this->visit_date,
                'weight' => $m['weight'],
                'height' => $m['height'],
                'measurement_method' => $m['measurement_method'],
                'nutrition_status' => $results['nutrition_status'],
                'z_score' => $results['z_score'],
                'stunting_status' => $results['stunting_status'],
                'z_score_hfa' => $results['z_score_hfa'],
                'wasting_status' => $results['wasting_status'],
                'z_score_wfh' => $results['z_score_wfh'],
            ]);
            $count++;
        }

        if ($count > 0) {
            session()->flash('success', "$count data penimbangan berhasil disimpan.");
            return redirect()->route('admin.medical-records.index');
        } else {
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'Tidak ada data baru yang disimpan.']);
        }
    }

    public function render()
    {
        $posyandus = Auth::user()->posyandu_id 
            ? Posyandu::where('id', Auth::user()->posyandu_id)->get()
            : Posyandu::all();

        return view('livewire.admin.medical-record.bulk-measurement-entry', [
            'posyandus' => $posyandus
        ])->layout('layouts.admin-layout');
    }
}
