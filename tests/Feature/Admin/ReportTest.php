<?php

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('local');

    $this->posyandu1 = Posyandu::factory()->create(['name' => 'Posyandu A']);
    $this->posyandu2 = Posyandu::factory()->create(['name' => 'Posyandu B']);

    $this->superadmin = User::factory()->create([
        'role' => 'superadmin',
        'posyandu_id' => null,
    ]);

    $this->admin = User::factory()->create([
        'role' => 'admin',
        'posyandu_id' => $this->posyandu1->id,
    ]);

    $this->coordinator = User::factory()->create([
        'role' => 'coordinator',
        'posyandu_id' => $this->posyandu1->id,
    ]);

    $this->staff = User::factory()->create([
        'role' => 'staff',
        'posyandu_id' => $this->posyandu1->id,
    ]);

    $this->medical = User::factory()->create([
        'role' => 'medical',
        'posyandu_id' => $this->posyandu1->id,
    ]);

    $this->patient = Patient::factory()->create([
        'posyandu_id' => $this->posyandu1->id,
        'category' => 'balita',
    ]);

    $this->medicalRecord = MedicalRecord::factory()->create([
        'patient_id' => $this->patient->id,
        'user_id' => $this->admin->id,
        'visit_date' => now(),
        'weight' => 10.0,
        'height' => 75.0,
    ]);
});

describe('akses laporan per role', function () {
    it('superadmin dapat mengakses halaman laporan', function () {
        $this->actingAs($this->superadmin);
        $response = $this->get('/admin/reports');
        $response->assertOk();
    });

    it('admin dapat mengakses halaman laporan', function () {
        $this->actingAs($this->admin);
        $response = $this->get('/admin/reports');
        $response->assertOk();
    });

    it('coordinator dapat mengakses halaman laporan', function () {
        $this->actingAs($this->coordinator);
        $response = $this->get('/admin/reports');
        $response->assertOk();
    });

    it('staff tidak dapat mengakses halaman laporan', function () {
        $this->actingAs($this->staff);
        $response = $this->get('/admin/reports');
        $response->assertForbidden();
    });

    it('medical tidak dapat mengakses halaman laporan', function () {
        $this->actingAs($this->medical);
        $response = $this->get('/admin/reports');
        $response->assertForbidden();
    });
});

describe('ekspor Excel', function () {
    it('dapat mengekspor laporan ke Excel', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/reports/export-excel', [
            'month' => now()->month,
            'year' => now()->year,
        ]);

        $response->assertOk();
        $response->assertDownload();
    });

    it('file Excel memiliki ekstensi .xlsx', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/reports/export-excel', [
            'month' => now()->month,
            'year' => now()->year,
        ]);

        $response->assertOk();
        $contentDisposition = $response->headers->get('Content-Disposition');
        expect($contentDisposition)->toContain('.xlsx');
    });

    it('admin menggunakan posyandu sendiri saat ekspor meskipun mengirim posyandu_id lain', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/reports/export-excel', [
            'posyandu_id' => $this->posyandu2->id,
            'month' => now()->month,
            'year' => now()->year,
        ]);

        $response->assertOk();
        $contentDisposition = $response->headers->get('Content-Disposition');
        // Because the controller forces the admin's own posyandu, the filename will contain 'Posyandu_A'
        expect($contentDisposition)->toContain('Posyandu_A');
    });

    it('superadmin dapat mengekspor Excel untuk posyandu tertentu', function () {
        $this->actingAs($this->superadmin);

        $response = $this->post('/admin/reports/export-excel', [
            'posyandu_id' => $this->posyandu1->id,
            'month' => now()->month,
            'year' => now()->year,
        ]);

        $response->assertOk();
    });

    it('staff tidak dapat mengekspor Excel', function () {
        $this->actingAs($this->staff);

        $response = $this->post('/admin/reports/export-excel', [
            'posyandu_id' => $this->posyandu1->id,
            'month' => now()->month,
            'year' => now()->year,
        ]);

        $response->assertForbidden();
    });

    it('membuat log aktivitas saat ekspor Excel', function () {
        $this->actingAs($this->admin);

        $this->post('/admin/reports/export-excel', [
            'month' => now()->month,
            'year' => now()->year,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->admin->id,
            'action_type' => 'export_report',
        ]);
    });
});

describe('ekspor PDF', function () {
    it('dapat mengekspor laporan ke PDF', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/reports/export-pdf', [
            'month' => now()->month,
            'year' => now()->year,
        ]);

        $response->assertOk();
        $response->assertDownload();
    });

    it('file PDF memiliki ekstensi .pdf', function () {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/reports/export-pdf', [
            'month' => now()->month,
            'year' => now()->year,
        ]);

        $response->assertOk();
        $contentDisposition = $response->headers->get('Content-Disposition');
        expect($contentDisposition)->toContain('.pdf');
    });

    it('membuat log aktivitas saat ekspor PDF', function () {
        $this->actingAs($this->admin);

        $this->post('/admin/reports/export-pdf', [
            'month' => now()->month,
            'year' => now()->year,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->admin->id,
            'action_type' => 'export_report',
        ]);
    });
});

describe('performa ekspor', function () {
    it('ekspor Excel selesai dalam waktu wajar', function () {
        $this->actingAs($this->admin);
        $startTime = microtime(true);
        $response = $this->post('/admin/reports/export-excel', [
            'month' => now()->month,
            'year' => now()->year,
        ]);
        $executionTime = microtime(true) - $startTime;

        $response->assertOk();
        expect($executionTime)->toBeLessThan(10);
    });
});
