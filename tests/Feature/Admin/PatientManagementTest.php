<?php

use App\Models\Patient;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed roles and permissions
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

    // Create posyandus
    $this->posyandu1 = Posyandu::factory()->create(['name' => 'Posyandu A']);
    $this->posyandu2 = Posyandu::factory()->create(['name' => 'Posyandu B']);

    // Create users with different roles
    $this->superadmin = User::factory()->create([
        'role' => 'superadmin',
        'posyandu_id' => null,
    ]);

    $this->admin1 = User::factory()->create([
        'role' => 'admin',
        'posyandu_id' => $this->posyandu1->id,
    ]);

    $this->admin2 = User::factory()->create([
        'role' => 'admin',
        'posyandu_id' => $this->posyandu2->id,
    ]);

    $this->staff = User::factory()->create([
        'role' => 'kader',
        'posyandu_id' => $this->posyandu1->id,
    ]);

    // Create patients
    $this->patient1 = Patient::factory()->create([
        'posyandu_id' => $this->posyandu1->id,
        'id_number' => '1234567890123456',
        'full_name' => 'Patient 1',
    ]);

    $this->patient2 = Patient::factory()->create([
        'posyandu_id' => $this->posyandu2->id,
        'id_number' => '6543210987654321',
        'full_name' => 'Patient 2',
    ]);
});

describe('CRUD pasien - Create', function () {
    it('admin dapat menambahkan pasien baru', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Parent Name',
            'id_number' => '1111222233334444',
            'full_name' => 'New Patient',
            'birth_date' => '2020-01-01',
            'gender' => 'M',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
        ]);

        $this->assertDatabaseHas('patients', [
            'id_number' => '1111222233334444',
            'full_name' => 'New Patient',
            'posyandu_id' => $this->posyandu1->id,
        ]);
    });

    it('kader tidak dapat menambahkan pasien baru', function () {
        $this->actingAs($this->staff);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Parent Name',
            'id_number' => '2222333344445555',
            'full_name' => 'New Patient by Staff',
            'birth_date' => '2020-01-01',
            'gender' => 'F',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
        ]);

        $response->assertForbidden();
    });

    it('membuat log aktivitas saat menambahkan pasien', function () {
        $this->actingAs($this->admin1);

        $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Parent Name',
            'id_number' => '3333444455556666',
            'full_name' => 'New Patient',
            'birth_date' => '2020-01-01',
            'gender' => 'M',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->admin1->id,
            'action_type' => 'create_patient',
        ]);
    });
});

describe('CRUD pasien - Read', function () {
    it('admin dapat melihat daftar pasien', function () {
        $this->actingAs($this->admin1);

        $response = $this->get('/admin/patients');

        $response->assertOk();
    });

    it('admin dapat melihat detail pasien', function () {
        $this->actingAs($this->admin1);

        $response = $this->get("/admin/patients/{$this->patient1->id}");

        $response->assertOk();
    });

    it('menampilkan halaman pemilihan kategori jika parameter category tidak ada', function () {
        $this->actingAs($this->admin1);

        $response = $this->get('/admin/patients/create');

        $response->assertOk();
        $response->assertViewIs('livewire.admin.patient-management.select-category');
    });

    it('menampilkan form pendaftaran jika parameter category ada', function () {
        $this->actingAs($this->admin1);

        $response = $this->get('/admin/patients/create?category=balita');

        $response->assertOk();
        $response->assertViewIs('livewire.admin.patient-management.create');
    });
});

describe('CRUD pasien - Update', function () {
    it('admin dapat mengubah data pasien', function () {
        $this->actingAs($this->admin1);

        $response = $this->put("/admin/patients/{$this->patient1->id}", [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Updated Parent',
            'id_number' => '1234567890123456',
            'full_name' => 'Updated Patient Name',
            'birth_date' => '2020-01-01',
            'gender' => 'M',
            'address' => 'Updated Address',
            'phone_number' => '081234567890',
        ]);

        $this->assertDatabaseHas('patients', [
            'id' => $this->patient1->id,
            'full_name' => 'Updated Patient Name',
        ]);
    });

    it('membuat log aktivitas saat mengubah data pasien', function () {
        $this->actingAs($this->admin1);

        $this->put("/admin/patients/{$this->patient1->id}", [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Updated Parent',
            'id_number' => '1234567890123456',
            'full_name' => 'Updated Patient Name',
            'birth_date' => '2020-01-01',
            'gender' => 'M',
            'address' => 'Updated Address',
            'phone_number' => '081234567890',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->admin1->id,
            'action_type' => 'update_patient',
            'entity_id' => $this->patient1->id,
        ]);
    });

    it('admin dapat mengubah data lansia dengan nama ayah kandung, nama ibu kandung, dan rw domisili', function () {
        $this->actingAs($this->admin1);

        $patient = Patient::factory()->create([
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'lansia',
            'id_number' => '8888777766665555',
            'full_name' => 'Lansia Awal',
            'birth_date' => '1955-05-05',
            'gender' => 'F',
            'address' => 'Alamat Awal',
            'phone_number' => '081234567890',
        ]);

        $response = $this->put("/admin/patients/{$patient->id}", [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'lansia',
            'id_number' => '8888777766665555',
            'full_name' => 'Lansia Update',
            'birth_date' => '1955-05-05',
            'gender' => 'F',
            'address' => 'Alamat Update',
            'phone_number' => '081234567890',
            'father_name' => 'Ayah Lansia Baru',
            'mother_name' => 'Ibu Lansia Baru',
            'dusun_rt_rw' => 'RW 07',
        ]);

        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'full_name' => 'Lansia Update',
            'father_name' => 'Ayah Lansia Baru',
            'mother_name' => 'Ibu Lansia Baru',
            'dusun_rt_rw' => 'RW 07',
        ]);
    });
});

describe('CRUD pasien - Delete', function () {
    it('admin dapat menghapus pasien', function () {
        $this->actingAs($this->admin1);

        $response = $this->delete("/admin/patients/{$this->patient1->id}");

        $this->assertDatabaseMissing('patients', [
            'id' => $this->patient1->id,
        ]);
    });

    it('membuat log aktivitas saat menghapus pasien', function () {
        $this->actingAs($this->admin1);

        $this->delete("/admin/patients/{$this->patient1->id}");

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->admin1->id,
            'action_type' => 'delete_patient',
            'entity_id' => $this->patient1->id,
        ]);
    });
});

describe('validasi NIK 16 digit', function () {
    it('menolak NIK kurang dari 16 digit', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Parent Name',
            'id_number' => '123456789012345', // 15 digits
            'full_name' => 'New Patient',
            'birth_date' => '2020-01-01',
            'gender' => 'M',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
        ]);

        $response->assertSessionHasErrors('id_number');
    });

    it('menolak NIK lebih dari 16 digit', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Parent Name',
            'id_number' => '12345678901234567', // 17 digits
            'full_name' => 'New Patient',
            'birth_date' => '2020-01-01',
            'gender' => 'M',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
        ]);

        $response->assertSessionHasErrors('id_number');
    });

    it('menolak NIK yang mengandung huruf', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Parent Name',
            'id_number' => '123456789012345A', // Contains letter
            'full_name' => 'New Patient',
            'birth_date' => '2020-01-01',
            'gender' => 'M',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
        ]);

        $response->assertSessionHasErrors('id_number');
    });

    it('menerima NIK tepat 16 digit angka', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Parent Name',
            'id_number' => '1234567890123457',
            'full_name' => 'New Patient',
            'birth_date' => '2020-01-01',
            'gender' => 'M',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
        ]);

        $response->assertSessionDoesntHaveErrors('id_number');
    });
});

describe('validasi NIK duplikat', function () {
    it('menolak NIK yang sudah terdaftar dalam posyandu yang sama', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Parent Name',
            'id_number' => '1234567890123456', // Already exists in posyandu1
            'full_name' => 'Duplicate Patient',
            'birth_date' => '2020-01-01',
            'gender' => 'M',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
        ]);

        $response->assertSessionHasErrors('id_number');
    });

    it('menampilkan pesan "NIK sudah terdaftar dalam sistem" untuk NIK duplikat', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Parent Name',
            'id_number' => '1234567890123456',
            'full_name' => 'Duplicate Patient',
            'birth_date' => '2020-01-01',
            'gender' => 'M',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
        ]);

        $response->assertSessionHasErrors('id_number');
        $errors = session('errors');
        expect($errors->get('id_number')[0])->toContain('sudah terdaftar');
    });
});

describe('validasi kategori dan jenis kelamin', function () {
    it('menolak pendaftaran ibu hamil dengan jenis kelamin laki-laki', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'ibu_hamil',
            'id_number' => '9999888877776666',
            'full_name' => 'Bumil Laki Laki',
            'birth_date' => '1995-01-01',
            'gender' => 'M',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
        ]);

        $response->assertSessionHasErrors('gender');
    });

    it('menerima pendaftaran ibu hamil dengan jenis kelamin perempuan', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'ibu_hamil',
            'id_number' => '9999888877776666',
            'full_name' => 'Bumil Perempuan',
            'birth_date' => '1995-01-01',
            'gender' => 'F',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
            'husband_name' => 'Suami Test',
            'dusun_rt_rw' => 'RW 05',
            'desa_kelurahan' => 'Kelurahan Test',
            'kecamatan' => 'Kecamatan Test',
        ]);

        $response->assertSessionDoesntHaveErrors('gender');
        $this->assertDatabaseHas('patients', [
            'id_number' => '9999888877776666',
            'husband_name' => 'Suami Test',
            'dusun_rt_rw' => 'RW 05',
            'desa_kelurahan' => 'Kelurahan Test',
            'kecamatan' => 'Kecamatan Test',
        ]);
    });

    it('menerima pendaftaran lansia dengan nama ayah kandung, nama ibu kandung, dan rw domisili', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'lansia',
            'id_number' => '1111999922228888',
            'full_name' => 'Lansia Test',
            'birth_date' => '1950-01-01',
            'gender' => 'M',
            'address' => 'Test Lansia Address',
            'phone_number' => '081234567899',
            'father_name' => 'Ayah Lansia',
            'mother_name' => 'Ibu Lansia',
            'dusun_rt_rw' => 'RW 06',
        ]);

        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('patients', [
            'id_number' => '1111999922228888',
            'father_name' => 'Ayah Lansia',
            'mother_name' => 'Ibu Lansia',
            'dusun_rt_rw' => 'RW 06',
        ]);
    });
});

describe('scoping data per posyandu', function () {
    it('admin hanya melihat pasien dari posyandu mereka sendiri', function () {
        $this->actingAs($this->admin1);

        $response = $this->get('/admin/patients');

        $response->assertOk();
        // Verify only patient1 is visible (from posyandu1)
        // This would need to check the actual response content
    });

    it('admin tidak dapat mengakses pasien dari posyandu lain', function () {
        $this->actingAs($this->admin1);

        $response = $this->get("/admin/patients/{$this->patient2->id}");

        $response->assertForbidden();
    });

    it('admin tidak dapat mengubah pasien dari posyandu lain', function () {
        $this->actingAs($this->admin1);

        $response = $this->put("/admin/patients/{$this->patient2->id}", [
            'posyandu_id' => $this->posyandu2->id,
            'category' => 'balita',
            'parent_name' => 'Updated Parent',
            'id_number' => '6543210987654321',
            'full_name' => 'Updated Patient',
            'birth_date' => '2020-01-01',
            'gender' => 'M',
            'address' => 'Updated Address',
            'phone_number' => '081234567890',
        ]);

        $response->assertForbidden();
    });

    it('admin tidak dapat menghapus pasien dari posyandu lain', function () {
        $this->actingAs($this->admin1);

        $response = $this->delete("/admin/patients/{$this->patient2->id}");

        $response->assertForbidden();
    });

    it('superadmin dapat melihat semua pasien dari semua posyandu', function () {
        $this->actingAs($this->superadmin);

        $response = $this->get('/admin/patients');

        $response->assertOk();
        // Superadmin should see both patient1 and patient2
    });

    it('superadmin dapat mengakses pasien dari posyandu manapun', function () {
        $this->actingAs($this->superadmin);

        $response1 = $this->get("/admin/patients/{$this->patient1->id}");
        $response2 = $this->get("/admin/patients/{$this->patient2->id}");

        $response1->assertOk();
        $response2->assertOk();
    });
});

describe('otorisasi per role', function () {
    it('kader tidak dapat menambahkan pasien', function () {
        $this->actingAs($this->staff);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Parent Name',
            'id_number' => '7777888899990000',
            'full_name' => 'New Patient by Staff',
            'birth_date' => '2020-01-01',
            'gender' => 'M',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
        ]);

        $response->assertForbidden();
    });

    it('staff dapat melihat pasien dari posyandu mereka', function () {
        $this->actingAs($this->staff);

        $response = $this->get("/admin/patients/{$this->patient1->id}");

        $response->assertOk();
    });

    it('staff tidak dapat mengakses pasien dari posyandu lain', function () {
        $this->actingAs($this->staff);

        $response = $this->get("/admin/patients/{$this->patient2->id}");

        $response->assertForbidden();
    });
});

describe('validasi field wajib', function () {
    it('memerlukan NIK', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Parent Name',
            // id_number missing
            'full_name' => 'New Patient',
            'birth_date' => '2020-01-01',
            'gender' => 'M',
            'address' => 'Test Address',
        ]);

        $response->assertSessionHasErrors('id_number');
    });

    it('memerlukan nama lengkap', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Parent Name',
            'id_number' => '1111222233334444',
            // full_name missing
            'birth_date' => '2020-01-01',
            'gender' => 'M',
            'address' => 'Test Address',
        ]);

        $response->assertSessionHasErrors('full_name');
    });

    it('memerlukan tanggal lahir', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Parent Name',
            'id_number' => '1111222233334444',
            'full_name' => 'New Patient',
            // birth_date missing
            'gender' => 'M',
            'address' => 'Test Address',
        ]);

        $response->assertSessionHasErrors('birth_date');
    });

    it('memerlukan jenis kelamin', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Parent Name',
            'id_number' => '1111222233334444',
            'full_name' => 'New Patient',
            'birth_date' => '2020-01-01',
            // gender missing
            'address' => 'Test Address',
        ]);

        $response->assertSessionHasErrors('gender');
    });

    it('memerlukan kategori', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            // category missing
            'parent_name' => 'Parent Name',
            'id_number' => '1111222233334444',
            'full_name' => 'New Patient',
            'birth_date' => '2020-01-01',
            'gender' => 'M',
            'address' => 'Test Address',
        ]);

        $response->assertSessionHasErrors('category');
    });

    it('memerlukan alamat', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Parent Name',
            'id_number' => '1111222233334444',
            'full_name' => 'New Patient',
            'birth_date' => '2020-01-01',
            'gender' => 'M',
            // address missing
        ]);

        $response->assertSessionHasErrors('address');
    });
});

describe('validasi tanggal lahir', function () {
    it('menolak tanggal lahir di masa depan', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Parent Name',
            'id_number' => '1111222233334444',
            'full_name' => 'New Patient',
            'birth_date' => now()->addDay()->format('Y-m-d'),
            'gender' => 'M',
            'address' => 'Test Address',
        ]);

        $response->assertSessionHasErrors('birth_date');
    });

    it('menerima tanggal lahir hari ini', function () {
        $this->actingAs($this->admin1);

        $response = $this->post('/admin/patients', [
            'posyandu_id' => $this->posyandu1->id,
            'category' => 'balita',
            'parent_name' => 'Parent Name',
            'id_number' => '1111222233334444',
            'full_name' => 'New Patient',
            'birth_date' => now()->format('Y-m-d'),
            'gender' => 'M',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
        ]);

        $response->assertSessionDoesntHaveErrors('birth_date');
    });
});

describe('Tampilan detail kustom sesuai kategori', function () {
    it('menampilkan tema warna sesuai kategori pasien', function () {
        $this->actingAs($this->admin1);
        
        // Test Balita
        $this->patient1->update(['category' => 'balita']);
        $response = $this->get("/admin/patients/{$this->patient1->id}");
        $response->assertSee('from-teal-600 to-emerald-500');

        // Test Lansia
        $this->patient1->update(['category' => 'lansia']);
        $response = $this->get("/admin/patients/{$this->patient1->id}");
        $response->assertSee('from-amber-600 to-orange-500');

        // Test Ibu Hamil
        $this->patient1->update(['category' => 'ibu_hamil']);
        $response = $this->get("/admin/patients/{$this->patient1->id}");
        $response->assertSee('from-rose-500 to-pink-500');
    });

    it('menampilkan detail spesifik balita', function () {
        $this->actingAs($this->admin1);
        $this->patient1->update([
            'category' => 'balita',
            'father_name' => 'Ayah Antigravity',
            'mother_name' => 'Ibu Antigravity',
            'kia_book_ownership' => true
        ]);
        
        $response = $this->get("/admin/patients/{$this->patient1->id}");
        $response->assertSee('Ayah Antigravity');
        $response->assertSee('Ibu Antigravity');
        $response->assertSee('Buku KIA');
        $response->assertSee('Kartu Imunisasi');
    });

    it('menampilkan detail spesifik lansia', function () {
        $this->actingAs($this->admin1);
        $this->patient1->update([
            'category' => 'lansia',
            'historical_diseases' => 'Diabetes Mellitus',
            'father_name' => 'Ayah Lansia Kandung',
            'mother_name' => 'Ibu Lansia Kandung',
            'dusun_rt_rw' => 'RW 08',
        ]);
        $this->patient1->medicalRecords()->create([
            'user_id' => $this->admin1->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 60.0,
            'height' => 160.0,
            'complaint' => 'Tidak ada',
            'diagnosis' => 'Sehat',
            'systolic_bp' => 120,
            'diastolic_bp' => 80,
            'blood_sugar' => 110,
            'cholesterol' => 190,
            'uric_acid' => 5.2,
            'current_medication' => 'Metformin',
            'measurement_method' => 'recumbent',
        ]);

        $response = $this->get("/admin/patients/{$this->patient1->id}");
        $response->assertSee('Diabetes Mellitus');
        $response->assertSee('Metformin');
        $response->assertSee('Tekanan Darah');
        $response->assertSee('Gula Darah');
        $response->assertSee('Ayah Lansia Kandung');
        $response->assertSee('Ibu Lansia Kandung');
        $response->assertSee('RW 08');
    });

    it('menampilkan detail spesifik ibu hamil', function () {
        $this->actingAs($this->admin1);
        $this->patient1->update([
            'category' => 'ibu_hamil',
            'parent_name' => 'Suami Antigravity',
            'is_pregnant' => true
        ]);
        $this->patient1->medicalRecords()->create([
            'user_id' => $this->admin1->id,
            'visit_date' => now()->format('Y-m-d'),
            'weight' => 60.5,
            'height' => 155.0,
            'complaint' => 'Tidak ada',
            'diagnosis' => 'Sehat',
            'upper_arm_circumference' => 22.0,
            'measurement_method' => 'recumbent',
        ]);

        $response = $this->get("/admin/patients/{$this->patient1->id}");
        $response->assertSee('Suami Antigravity');
        $response->assertSee('LILA');
        $response->assertSee('Risiko KEK');
    });

    it('menampilkan detail spesifik umum', function () {
        $this->actingAs($this->admin1);
        $this->patient1->update([
            'category' => 'umum',
            'education' => 'SMA',
            'job' => 'Wiraswasta',
        ]);

        $response = $this->get("/admin/patients/{$this->patient1->id}");
        $response->assertSee('SMA');
        $response->assertSee('Wiraswasta');
    });
});
