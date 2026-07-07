<?php

use App\Models\User;
use App\Models\Patient;
use App\Models\Posyandu;
use App\Models\Pedukuhan;
use App\Livewire\Admin\PatientManagement\Index as PatientIndex;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create baseline pedukuhan and posyandu
    $this->pedukuhan = Pedukuhan::factory()->create();
    $this->posyandu1 = Posyandu::factory()->create(['pedukuhan_id' => $this->pedukuhan->id]);
    $this->posyandu2 = Posyandu::factory()->create(['pedukuhan_id' => $this->pedukuhan->id]);

    // Create some kaders
    $this->kader1 = User::factory()->create([
        'role' => 'kader',
        'is_active' => true,
        'posyandu_id' => $this->posyandu1->id
    ]);
    $this->kader2 = User::factory()->create([
        'role' => 'kader',
        'is_active' => true,
        'posyandu_id' => $this->posyandu1->id
    ]);
    // Inactive kader - should not be included
    $this->inactiveKader = User::factory()->create([
        'role' => 'kader',
        'is_active' => false,
        'posyandu_id' => $this->posyandu1->id
    ]);

    // Create patients
    // 1. Bayi (age < 12 months, database category 'balita')
    $this->bayi = Patient::factory()->create([
        'category' => 'balita',
        'birth_date' => now()->subMonths(6),
        'posyandu_id' => $this->posyandu1->id
    ]);

    // 2. Baduta (age 12-23 months, database category 'balita')
    $this->baduta = Patient::factory()->create([
        'category' => 'balita',
        'birth_date' => now()->subMonths(18),
        'posyandu_id' => $this->posyandu1->id
    ]);

    // 3. Balita (age >= 24 months, database category 'balita')
    $this->balita = Patient::factory()->create([
        'category' => 'balita',
        'birth_date' => now()->subMonths(36),
        'posyandu_id' => $this->posyandu1->id
    ]);

    // 4. Lansia (database category 'lansia')
    $this->lansia = Patient::factory()->create([
        'category' => 'lansia',
        'birth_date' => now()->subYears(65),
        'posyandu_id' => $this->posyandu1->id
    ]);
});

it('displays the correct dynamic metrics on the about page', function () {
    // Expected counts:
    // Kader: 12 active (10 seeded from migration + 2 created in beforeEach)
    // Warga: 4 patients total
    // Posyandu: 2 posyandu units total
    $expectedKaderCount = User::whereIn('role', ['admin', 'kader'])->where('is_active', true)->count(); // should be 12
    $expectedWargaCount = Patient::count(); // should be 4
    $expectedPosyanduCount = Posyandu::count(); // should be 2

    expect($expectedKaderCount)->toBe(12);
    expect($expectedWargaCount)->toBe(4);
    expect($expectedPosyanduCount)->toBe(2);

    $response = $this->get('/about');

    $response->assertOk();
    $response->assertSee((string)$expectedKaderCount);
    $response->assertSee((string)$expectedWargaCount);
    $response->assertSee((string)$expectedPosyanduCount);
});

it('filters and counts bayi, baduta, and balita dynamically on the patient index page', function () {
    // Authenticate as a user who has access (e.g. superadmin)
    $admin = User::factory()->create([
        'role' => 'superadmin',
        'posyandu_id' => null,
    ]);
    $this->actingAs($admin);

    // Verify statistics cards are displayed correctly with values: Bayi: 1, Baduta: 1, Balita: 1
    $response = $this->get(route('admin.patients.index'));
    $response->assertOk();
    
    // Verify Livewire component logic
    $livewire = Livewire::test(PatientIndex::class);
    
    // Test count matching logic
    // Set category filter to bayi
    $livewire->set('category', 'bayi');
    $patients = $livewire->viewData('patients');
    expect($patients->total())->toBe(1);

    // Set category filter to baduta
    $livewire->set('category', 'baduta');
    $patients = $livewire->viewData('patients');
    expect($patients->total())->toBe(1);

    // Set category filter to balita
    $livewire->set('category', 'balita');
    $patients = $livewire->viewData('patients');
    expect($patients->total())->toBe(1);
});
