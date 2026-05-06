<?php

use App\Livewire\Admin\PatientManagement\GrowthChart;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create a posyandu
    $this->posyandu = Posyandu::factory()->create();

    // Create an admin user
    $this->admin = User::factory()->create([
        'role' => 'admin',
        'posyandu_id' => $this->posyandu->id,
    ]);
});

it('displays growth chart for balita patient with medical records', function () {
    // Create a balita patient
    $patient = Patient::factory()->create([
        'posyandu_id' => $this->posyandu->id,
        'category' => 'balita',
        'birth_date' => now()->subMonths(24),
    ]);

    // Create medical records
    MedicalRecord::factory()->create([
        'patient_id' => $patient->id,
        'user_id' => $this->admin->id,
        'visit_date' => now()->subMonths(2),
        'weight' => 12.5,
        'height' => 85.0,
        'head_circumference' => 46.0,
        'immunization' => 'BCG',
        'complaint' => 'Sehat',
        'diagnosis' => 'Gizi Baik',
        'nutrition_status' => 'Gizi Baik',
        'z_score' => 0.5,
    ]);

    MedicalRecord::factory()->create([
        'patient_id' => $patient->id,
        'user_id' => $this->admin->id,
        'visit_date' => now()->subMonth(),
        'weight' => 13.0,
        'height' => 86.5,
        'head_circumference' => 46.5,
        'immunization' => 'DPT',
        'complaint' => 'Sehat',
        'diagnosis' => 'Gizi Baik',
        'nutrition_status' => 'Gizi Baik',
        'z_score' => 0.6,
    ]);

    // Test Livewire component
    Livewire::actingAs($this->admin)
        ->test(GrowthChart::class, ['patient' => $patient])
        ->assertSee('Grafik Pertumbuhan')
        ->assertSee('Berat Badan (kg)')
        ->assertSee('Tinggi Badan (cm)')
        ->assertSet('chartData.weight', function ($weight) {
            return count($weight) === 2 && $weight[0] == 12.5 && $weight[1] == 13.0;
        })
        ->assertSet('chartData.height', function ($height) {
            return count($height) === 2 && $height[0] == 85.0 && $height[1] == 86.5;
        });
});

it('shows empty state when patient has no medical records', function () {
    // Create a balita patient without medical records
    $patient = Patient::factory()->create([
        'posyandu_id' => $this->posyandu->id,
        'category' => 'balita',
        'birth_date' => now()->subMonths(24),
    ]);

    // Test Livewire component
    Livewire::actingAs($this->admin)
        ->test(GrowthChart::class, ['patient' => $patient])
        ->assertSee('Belum ada data pengukuran untuk ditampilkan di grafik');
});

it('displays growth chart on patient detail page for balita', function () {
    // Create a balita patient
    $patient = Patient::factory()->create([
        'posyandu_id' => $this->posyandu->id,
        'category' => 'balita',
        'birth_date' => now()->subMonths(24),
    ]);

    // Visit patient detail page
    $this->actingAs($this->admin)
        ->get(route('admin.patients.show', $patient))
        ->assertOk()
        ->assertSeeLivewire('admin.patient-management.growth-chart');
});

it('does not display growth chart for non-balita patients', function () {
    // Create an ibu_hamil patient
    $patient = Patient::factory()->create([
        'posyandu_id' => $this->posyandu->id,
        'category' => 'ibu_hamil',
        'birth_date' => now()->subYears(25),
    ]);

    // Visit patient detail page
    $this->actingAs($this->admin)
        ->get(route('admin.patients.show', $patient))
        ->assertOk()
        ->assertDontSeeLivewire('admin.patient-management.growth-chart');
});

it('color codes data points based on nutrition status', function () {
    // Create a balita patient
    $patient = Patient::factory()->create([
        'posyandu_id' => $this->posyandu->id,
        'category' => 'balita',
        'birth_date' => now()->subMonths(24),
    ]);

    // Create medical records with different nutrition statuses
    MedicalRecord::factory()->create([
        'patient_id' => $patient->id,
        'user_id' => $this->admin->id,
        'visit_date' => now()->subMonths(3),
        'weight' => 10.0,
        'height' => 80.0,
        'immunization' => 'BCG',
        'complaint' => 'Berat badan kurang',
        'diagnosis' => 'Gizi buruk',
        'nutrition_status' => 'Gizi Buruk',
        'z_score' => -3.5,
    ]);

    MedicalRecord::factory()->create([
        'patient_id' => $patient->id,
        'user_id' => $this->admin->id,
        'visit_date' => now()->subMonths(2),
        'weight' => 11.0,
        'height' => 82.0,
        'immunization' => 'DPT',
        'complaint' => 'Berat badan kurang',
        'diagnosis' => 'Gizi kurang',
        'nutrition_status' => 'Gizi Kurang',
        'z_score' => -2.5,
    ]);

    MedicalRecord::factory()->create([
        'patient_id' => $patient->id,
        'user_id' => $this->admin->id,
        'visit_date' => now()->subMonth(),
        'weight' => 12.5,
        'height' => 85.0,
        'immunization' => 'Polio',
        'complaint' => 'Sehat',
        'diagnosis' => 'Gizi Baik',
        'nutrition_status' => 'Gizi Baik',
        'z_score' => 0.5,
    ]);

    // Test Livewire component
    Livewire::actingAs($this->admin)
        ->test(GrowthChart::class, ['patient' => $patient])
        ->assertSet('chartData.nutrition_status', function ($statuses) {
            return count($statuses) === 3
                && $statuses[0] === 'Gizi Buruk'
                && $statuses[1] === 'Gizi Kurang'
                && $statuses[2] === 'Gizi Baik';
        });
});
