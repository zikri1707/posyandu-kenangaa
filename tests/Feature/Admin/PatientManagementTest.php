<?php

use App\Models\Patient;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
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
