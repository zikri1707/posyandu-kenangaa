<?php

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create a pedukuhan manually
    $pedukuhan = \App\Models\Pedukuhan::create([
        'name' => 'Test Pedukuhan',
        'postal_code' => '12345',
        'geo_location' => '-6.2088,106.8456',
    ]);

    // Create a posyandu
    $this->posyandu = Posyandu::create([
        'pedukuhan_id' => $pedukuhan->id,
        'name' => 'Test Posyandu',
        'address' => 'Test Address',
        'unique_code' => 'POS001',
    ]);

    // Create an admin user
    $this->user = User::factory()->create([
        'role' => 'admin',
        'posyandu_id' => $this->posyandu->id,
    ]);

    // Create a balita patient
    $this->patient = Patient::create([
        'posyandu_id' => $this->posyandu->id,
        'category' => 'balita',
        'parent_name' => 'Test Parent',
        'id_number' => '1234567890123456',
        'full_name' => 'Test Balita',
        'birth_date' => now()->subMonths(12), // 12 months old
        'gender' => 'M',
        'address' => 'Test Address',
        'phone_number' => '08123456789',
    ]);

    // Seed WHO reference data for testing
    \App\Models\WhoWeightForAge::create([
        'gender' => 'M',
        'age_months' => 12,
        'sd_minus3' => 7.7,
        'sd_minus2' => 8.6,
        'median' => 10.2,
        'sd_plus2' => 12.0,
        'sd_plus3' => 13.3,
    ]);
});

test('medical record automatically calculates nutrition status for balita on store', function () {
    $this->actingAs($this->user);

    $medicalRecordData = [
        'patient_id' => $this->patient->id,
        'user_id' => $this->user->id,
        'visit_date' => now()->format('Y-m-d'),
        'weight' => 9.5,
        'height' => 75.0,
        'head_circumference' => 46.0,
        'immunization' => 'BCG',
        'diagnosis' => 'Sehat',
        'measurement_method' => 'standing',
        'complaint' => 'Tidak ada keluhan',
    ];

    $response = $this->post(route('admin.medical-records.store'), $medicalRecordData);

    $response->assertRedirect(route('admin.medical-records.index'));

    // Verify medical record was created with nutrition status
    $medicalRecord = MedicalRecord::where('patient_id', $this->patient->id)->first();

    expect($medicalRecord)->not->toBeNull()
        ->and($medicalRecord->z_score)->not->toBeNull()
        ->and($medicalRecord->z_score)->toBeNumeric()
        ->and($medicalRecord->nutrition_status)->not->toBeNull()
        ->and($medicalRecord->nutrition_status)->toBeIn([
            'Gizi Baik', 'Gizi Kurang', 'Gizi Lebih', 'Gizi Buruk', 'Tidak Dapat Dihitung',
        ]);
});

test('medical record recalculates nutrition status when weight changes on update', function () {
    $this->actingAs($this->user);

    // Create initial medical record
    $medicalRecord = MedicalRecord::create([
        'patient_id' => $this->patient->id,
        'user_id' => $this->user->id,
        'visit_date' => now()->format('Y-m-d'),
        'weight' => 9.5,
        'height' => 75.0,
        'head_circumference' => 46.0,
        'immunization' => 'BCG',
        'diagnosis' => 'Sehat',
        'complaint' => 'Tidak ada keluhan',
        'z_score' => null,
        'nutrition_status' => null,
    ]);

    $oldZScore = $medicalRecord->z_score;

    // Update with new weight
    $updateData = [
        'patient_id' => $this->patient->id,
        'user_id' => $this->user->id,
        'visit_date' => $medicalRecord->visit_date->format('Y-m-d'),
        'weight' => 10.5, // Changed weight
        'height' => 75.0,
        'head_circumference' => 46.0,
        'immunization' => 'BCG',
        'diagnosis' => 'Sehat',
        'measurement_method' => 'standing',
        'complaint' => 'Tidak ada keluhan',
    ];

    $response = $this->put(route('admin.medical-records.update', $medicalRecord), $updateData);

    $response->assertRedirect(route('admin.medical-records.index'));

    // Verify nutrition status was recalculated
    $medicalRecord->refresh();

    expect($medicalRecord->z_score)->not->toBeNull()
        ->and($medicalRecord->nutrition_status)->not->toBeNull()
        ->and($medicalRecord->z_score)->not->toBe($oldZScore);
});

test('medical record calculates nutrition trend by comparing with previous record', function () {
    $this->actingAs($this->user);

    // Create first medical record with Normal status
    $firstRecord = MedicalRecord::create([
        'patient_id' => $this->patient->id,
        'user_id' => $this->user->id,
        'visit_date' => now()->subMonth()->format('Y-m-d'),
        'weight' => 9.5,
        'height' => 75.0,
        'head_circumference' => 46.0,
        'immunization' => 'BCG',
        'diagnosis' => 'Sehat',
        'complaint' => 'Tidak ada keluhan',
        'nutrition_status' => 'Gizi Baik',
        'z_score' => 0.0,
    ]);

    // Create second medical record
    $secondRecordData = [
        'patient_id' => $this->patient->id,
        'user_id' => $this->user->id,
        'visit_date' => now()->format('Y-m-d'),
        'weight' => 8.0, // Lower weight - should worsen status
        'height' => 76.0,
        'head_circumference' => 46.0,
        'immunization' => 'DPT',
        'diagnosis' => 'Sehat',
        'measurement_method' => 'standing',
        'complaint' => 'Tidak ada keluhan',
    ];

    $response = $this->post(route('admin.medical-records.store'), $secondRecordData);

    $response->assertSessionDoesntHaveErrors();
    $response->assertRedirect(route('admin.medical-records.index'));

    // Verify nutrition trend was calculated
    $secondRecord = MedicalRecord::where('patient_id', $this->patient->id)
        ->latest('id')
        ->first();

    expect($secondRecord)->not->toBeNull()
        ->and($secondRecord->nutrition_trend)->not->toBeNull()
        ->and($secondRecord->nutrition_trend)->toBeIn(['naik', 'turun', 'tetap']);
});

test('medical record does not calculate nutrition status for non-balita categories', function () {
    $this->actingAs($this->user);

    // Create a non-balita patient
    $adultPatient = Patient::create([
        'posyandu_id' => $this->posyandu->id,
        'category' => 'lansia',
        'parent_name' => 'Test Parent',
        'id_number' => '1234567890123457',
        'full_name' => 'Test Lansia',
        'birth_date' => now()->subYears(65),
        'gender' => 'M',
        'address' => 'Test Address',
        'phone_number' => '08123456789',
    ]);

    $medicalRecordData = [
        'patient_id' => $adultPatient->id,
        'user_id' => $this->user->id,
        'visit_date' => now()->format('Y-m-d'),
        'weight' => 70.0,
        'height' => 170.0,
        'immunization' => 'Influenza',
        'diagnosis' => 'Sehat',
        'measurement_method' => 'standing',
        'complaint' => 'Tidak ada keluhan',
    ];

    $response = $this->post(route('admin.medical-records.store'), $medicalRecordData);

    $response->assertRedirect(route('admin.medical-records.index'));

    // Verify medical record was created without nutrition status
    $medicalRecord = MedicalRecord::where('patient_id', $adultPatient->id)->first();

    expect($medicalRecord)->not->toBeNull()
        ->and($medicalRecord->z_score)->toBeNull()
        ->and($medicalRecord->nutrition_status)->toBeNull();
});

test('compareNutritionStatus returns correct trend values', function () {
    $service = app(\App\Services\MedicalRecordService::class);
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('compareNutritionStatus');
    $method->setAccessible(true);

    // Test improvement: Gizi Kurang -> Gizi Baik
    $trend = $method->invoke($service, 'Gizi Kurang', 'Gizi Baik');
    expect($trend)->toBe('naik');

    // Test worsening: Gizi Baik -> Gizi Kurang
    $trend = $method->invoke($service, 'Gizi Baik', 'Gizi Kurang');
    expect($trend)->toBe('turun');

    // Test same: Gizi Baik -> Gizi Baik
    $trend = $method->invoke($service, 'Gizi Baik', 'Gizi Baik');
    expect($trend)->toBe('tetap');

    // Test severe worsening: Gizi Baik -> Gizi Buruk
    $trend = $method->invoke($service, 'Gizi Baik', 'Gizi Buruk');
    expect($trend)->toBe('turun');

    // Test improvement from severe: Gizi Buruk -> Gizi Kurang
    $trend = $method->invoke($service, 'Gizi Buruk', 'Gizi Kurang');
    expect($trend)->toBe('naik');
});
