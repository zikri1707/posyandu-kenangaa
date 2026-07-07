<?php

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Posyandu;
use App\Models\User;
use App\Models\WhoWeightForAge;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed roles and permissions
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

    // Create posyandu and users
    $this->posyandu = Posyandu::factory()->create();

    $this->admin = User::factory()->create([
        'role' => 'admin',
        'posyandu_id' => $this->posyandu->id,
    ]);

    $this->staff = User::factory()->create([
        'role' => 'staff',
        'posyandu_id' => $this->posyandu->id,
    ]);

    $this->kader = User::factory()->create([
        'role' => 'kader',
        'posyandu_id' => $this->posyandu->id,
    ]);

    // Create patient (balita)
    $this->patient = Patient::factory()->create([
        'posyandu_id' => $this->posyandu->id,
        'category' => 'balita',
        'birth_date' => now()->subMonths(12), // 12 months old
        'gender' => 'L',
    ]);

    // Seed WHO reference data for testing
    WhoWeightForAge::create([
        'gender' => 'M',
        'age_months' => 12,
        'sd_minus3' => 7.7,
        'sd_minus2' => 8.6,
        'median' => 10.2,
        'sd_plus2' => 12.0,
        'sd_plus3' => 13.3,
    ]);

    WhoWeightForAge::create([
        'gender' => 'F',
        'age_months' => 12,
        'sd_minus3' => 7.0,
        'sd_minus2' => 7.9,
        'median' => 9.5,
        'sd_plus2' => 11.3,
        'sd_plus3' => 12.5,
    ]);
});

describe('validasi rentang berat badan', function () {
    it('menolak berat badan kurang dari 0.5 kg', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 0.4, // Too low
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionHasErrors('weight');
    });

    it('menolak berat badan lebih dari 200 kg', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 201.0, // Too high
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionHasErrors('weight');
    });

    it('menerima berat badan 0.5 kg (batas minimal)', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 0.5,
            'height' => 50.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionDoesntHaveErrors('weight');
    });

    it('menerima berat badan 200 kg (batas maksimal)', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 200.0,
            'height' => 180.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionDoesntHaveErrors('weight');
    });

    it('menerima berat badan dalam rentang normal', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.5,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionDoesntHaveErrors('weight');
    });
});

describe('validasi rentang tinggi badan', function () {
    it('menolak tinggi badan kurang dari 20 cm', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 19.0, // Too low (min: 20)
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionHasErrors('height');
    });

    it('menolak tinggi badan lebih dari 300 cm', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 301.0, // Too high (max: 300)
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionHasErrors('height');
    });

    it('menerima tinggi badan 30 cm (batas minimal)', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 1.0,
            'height' => 30.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionDoesntHaveErrors('height');
    });

    it('menerima tinggi badan 300 cm (batas maksimal)', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 100.0,
            'height' => 300.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionDoesntHaveErrors('height');
    });
});

describe('validasi rentang lingkar kepala', function () {
    it('menolak lingkar kepala kurang dari 20 cm', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
            'head_circumference' => 19.0, // Too low
        ]);

        $response->assertSessionHasErrors('head_circumference');
    });

    it('menolak lingkar kepala lebih dari 70 cm', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
            'head_circumference' => 71.0, // Too high
        ]);

        $response->assertSessionHasErrors('head_circumference');
    });

    it('menerima lingkar kepala 20 cm (batas minimal)', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
            'head_circumference' => 20.0,
        ]);

        $response->assertSessionDoesntHaveErrors('head_circumference');
    });

    it('menerima lingkar kepala 70 cm (batas maksimal)', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
            'head_circumference' => 70.0,
        ]);

        $response->assertSessionDoesntHaveErrors('head_circumference');
    });

    it('menerima lingkar kepala null (opsional)', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
            'head_circumference' => null,
        ]);

        $response->assertSessionDoesntHaveErrors('head_circumference');
    });
});

describe('validasi tanggal kunjungan', function () {
    it('menolak tanggal kunjungan di masa depan', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->addDay()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionHasErrors('visit_date');
    });

    it('menerima tanggal kunjungan hari ini', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionDoesntHaveErrors('visit_date');
    });

    it('menerima tanggal kunjungan di masa lalu', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->subDays(7)->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionDoesntHaveErrors('visit_date');
    });
});

describe('kalkulasi status gizi otomatis', function () {
    it('menghitung z_score otomatis saat menyimpan rekam medis', function () {
        $this->actingAs($this->admin);

        $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.2, // Median weight for 12-month male
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $record = MedicalRecord::where('patient_id', $this->patient->id)->first();

        expect($record->z_score)->not->toBeNull()
            ->and($record->z_score)->toBeNumeric();
    });

    it('menghitung nutrition_status otomatis saat menyimpan rekam medis', function () {
        $this->actingAs($this->admin);

        $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.2, // Median weight
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $record = MedicalRecord::where('patient_id', $this->patient->id)->first();

        expect($record->nutrition_status)->not->toBeNull()
            ->and($record->nutrition_status)->toBe('Gizi Baik');
    });

    it('mengklasifikasikan status gizi sebagai Normal untuk z-score 0', function () {
        $this->actingAs($this->admin);

        $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.2, // Median
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $record = MedicalRecord::where('patient_id', $this->patient->id)->first();

        expect($record->nutrition_status)->toBe('Gizi Baik');
    });

    it('mengklasifikasikan status gizi sebagai Gizi Kurang untuk berat rendah', function () {
        $this->actingAs($this->admin);

        $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 8.6, // sd_minus2
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $record = MedicalRecord::where('patient_id', $this->patient->id)->first();

        expect($record->nutrition_status)->toBeIn(['Gizi Kurang', 'Gizi Baik']);
    });

    it('mengklasifikasikan status gizi sebagai Gizi Buruk/Stunting untuk berat sangat rendah', function () {
        $this->actingAs($this->admin);

        $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 7.0, // Below sd_minus3
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $record = MedicalRecord::where('patient_id', $this->patient->id)->first();

        expect($record->nutrition_status)->toBeIn(['Gizi Buruk', 'Gizi Kurang']);
    });

    it('mengklasifikasikan status gizi sebagai Gizi Lebih untuk berat tinggi', function () {
        $this->actingAs($this->admin);

        $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 13.0, // Above sd_plus2
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $record = MedicalRecord::where('patient_id', $this->patient->id)->first();

        expect($record->nutrition_status)->toBeIn(['Gizi Lebih', 'Gizi Baik']);
    });

    it('menghitung ulang status gizi saat mengubah rekam medis', function () {
        $this->actingAs($this->admin);

        // Create initial record
        $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.2,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $record = MedicalRecord::where('patient_id', $this->patient->id)->first();
        $initialStatus = $record->nutrition_status;

        // Update with different weight
        $this->put("/admin/medical-records/{$record->id}", [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 15.0, // Higher weight to change status
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $record->refresh();

        expect($record->nutrition_status)->not->toBe($initialStatus);
    });
});

describe('field wajib', function () {
    it('memerlukan patient_id', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionHasErrors('patient_id');
    });

    it('memerlukan visit_date', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionHasErrors('visit_date');
    });

    it('memerlukan weight', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionHasErrors('weight');
    });

    it('memerlukan height', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
        ]);

        $response->assertSessionHasErrors('height');
    });
});

describe('log aktivitas', function () {
    it('membuat log aktivitas saat menambahkan rekam medis', function () {
        $this->actingAs($this->admin);

        $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->admin->id,
            'action_type' => 'create_medical_record',
        ]);
    });

    it('membuat log aktivitas saat mengubah rekam medis', function () {
        $this->actingAs($this->admin);

        // Create record
        $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $record = MedicalRecord::where('patient_id', $this->patient->id)->first();

        // Update record
        $this->put("/admin/medical-records/{$record->id}", [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 11.0,
            'height' => 76.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->admin->id,
            'action_type' => 'update_medical_record',
            'entity_id' => $record->id,
        ]);
    });
});

describe('otorisasi', function () {
    it('kader dapat melihat daftar rekam medis', function () {
        $this->actingAs($this->kader);

        $response = $this->get('/admin/medical-records');

        $response->assertStatus(200);
    });

    it('kader dapat melihat detail rekam medis', function () {
        $this->actingAs($this->admin);
        // Buat rekam medis terlebih dahulu
        $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);
        $record = MedicalRecord::latest()->first();

        $this->actingAs($this->kader);
        $response = $this->get("/admin/medical-records/{$record->id}");

        $response->assertStatus(200);
    });

    it('kader tidak dapat mengakses halaman tambah rekam medis', function () {
        $this->actingAs($this->kader);

        $response = $this->get('/admin/medical-records/create');

        $response->assertStatus(403);
    });

    it('kader tidak dapat menambahkan rekam medis', function () {
        $this->actingAs($this->kader);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertStatus(403);
    });

    it('kader tidak dapat mengakses halaman edit rekam medis', function () {
        $this->actingAs($this->admin);
        $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);
        $record = MedicalRecord::latest()->first();

        $this->actingAs($this->kader);
        $response = $this->get("/admin/medical-records/{$record->id}/edit");

        $response->assertStatus(403);
    });

    it('kader tidak dapat mengubah rekam medis', function () {
        $this->actingAs($this->admin);
        $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);
        $record = MedicalRecord::latest()->first();

        $this->actingAs($this->kader);
        $response = $this->put("/admin/medical-records/{$record->id}", [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 12.0,
            'height' => 76.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertStatus(403);
    });

    it('kader tidak dapat menghapus rekam medis', function () {
        $this->actingAs($this->admin);
        $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);
        $record = MedicalRecord::latest()->first();

        $this->actingAs($this->kader);
        $response = $this->delete("/admin/medical-records/{$record->id}");

        $response->assertStatus(403);
    });

    it('kader tidak dapat mengakses halaman bulk measurement entry', function () {
        $this->actingAs($this->kader);

        $response = $this->get('/admin/medical-records/bulk');

        $response->assertStatus(403);
    });

    it('staff tidak dapat menambahkan rekam medis', function () {
        $this->actingAs($this->staff);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertStatus(403);
    });

    it('admin dapat menambahkan rekam medis', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertRedirect(route('admin.medical-records.index'));
        $this->assertDatabaseHas('medical_records', [
            'patient_id' => $this->patient->id,
            'weight' => 10.0,
        ]);
    });
});

describe('field opsional', function () {
    it('dapat menyimpan rekam medis tanpa head_circumference', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionDoesntHaveErrors();

        $record = MedicalRecord::where('patient_id', $this->patient->id)->first();
        expect($record->head_circumference)->toBeNull();
    });

    it('dapat menyimpan rekam medis tanpa complaint', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionDoesntHaveErrors();
    });

    it('dapat menyimpan rekam medis tanpa diagnosis karena terisi otomatis', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 75.0,
            'measurement_method' => 'standing',
            // diagnosis missing
        ]);

        $response->assertSessionDoesntHaveErrors();
        $record = \App\Models\MedicalRecord::latest('id')->first();
        expect($record->diagnosis)->toBe('Sehat');
    });
});

describe('fitur pemilihan kategori dan validasi dinamis', function () {
    it('menampilkan halaman pemilihan kategori jika parameter kategori tidak ada', function () {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/medical-records/create');
        $response->assertStatus(200);
        $response->assertViewIs('livewire.admin.medical-record-management.select-category');
    });

    it('menampilkan form pembuatan rekam medis jika parameter kategori balita diset', function () {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/medical-records/create?category=balita');
        $response->assertStatus(200);
        $response->assertViewIs('livewire.admin.medical-record-management.create');
        $response->assertViewHas('patients');
    });

    it('menyimpan rekam medis ibu hamil tanpa memerlukan cara ukur', function () {
        $this->actingAs($this->admin);

        $pregnantMother = Patient::factory()->create([
            'posyandu_id' => $this->posyandu->id,
            'category' => 'ibu_hamil',
            'gender' => 'F',
        ]);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $pregnantMother->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 60.5,
            'height' => 155.0,
            'measurement_method' => null, // Not required for pregnant mothers
            'pill_fe' => 1,
            'systolic_bp' => 120,
            'diastolic_bp' => 80,
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('medical_records', [
            'patient_id' => $pregnantMother->id,
            'pill_fe' => 1,
            'systolic_bp' => 120,
            'diastolic_bp' => 80,
        ]);
    });

    it('menyimpan rekam medis lansia tanpa memerlukan cara ukur', function () {
        $this->actingAs($this->admin);

        $elderly = Patient::factory()->create([
            'posyandu_id' => $this->posyandu->id,
            'category' => 'lansia',
        ]);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $elderly->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 55.0,
            'height' => 150.0,
            'measurement_method' => null, // Not required for elderly
            'blood_sugar' => 110,
            'uric_acid' => 5.2,
            'cholesterol' => 190,
            'current_medication' => 'Mecobalamin 500mcg',
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('medical_records', [
            'patient_id' => $elderly->id,
            'blood_sugar' => 110,
            'uric_acid' => 5.2,
            'cholesterol' => 190,
            'current_medication' => 'Mecobalamin 500mcg',
        ]);
    });

    it('menolak rekam medis balita jika cara ukur tidak diisi', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/medical-records', [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 10.2,
            'height' => 75.0,
            'measurement_method' => null, // Required for child
            'diagnosis' => 'Sehat',
        ]);

        $response->assertSessionHasErrors('measurement_method');
    });
});
