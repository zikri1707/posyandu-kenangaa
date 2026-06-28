<?php

/**
 * @var Tests\TestCase $this
 * @property \App\Models\User $user
 * @property \App\Services\ActivityLogService $service
 */

use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create a test user and authenticate
    $this->user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'role' => 'admin',
    ]);

    Auth::login($this->user);

    $this->service = new ActivityLogService;
});

describe('log method', function () {
    it('membuat log aktivitas dengan atribut lengkap', function () {
        $log = $this->service->log(
            actionType: 'create_patient',
            description: 'Menambahkan data pasien baru',
            entityId: 123,
            entityType: 'Patient',
            oldValues: null,
            newValues: ['name' => 'John Doe', 'nik' => '1234567890123456']
        );

        expect($log)->toBeInstanceOf(ActivityLog::class)
            ->and($log->user_id)->toBe($this->user->id)
            ->and($log->user_name)->toBe($this->user->name)
            ->and($log->role)->toBe($this->user->role)
            ->and($log->action_type)->toBe('create_patient')
            ->and($log->description)->toBe('Menambahkan data pasien baru')
            ->and($log->entity_id)->toBe(123)
            ->and($log->entity_type)->toBe('Patient')
            ->and($log->old_values)->toBeNull()
            ->and($log->new_values)->toBe(['name' => 'John Doe', 'nik' => '1234567890123456'])
            ->and($log->ip_address)->not->toBeNull();
    });

    it('membuat log dengan user_id dari pengguna yang terautentikasi', function () {
        $log = $this->service->log('login', 'User berhasil login');

        expect($log->user_id)->toBe($this->user->id);
    });

    it('membuat log dengan user_name dari pengguna yang terautentikasi', function () {
        $log = $this->service->log('login', 'User berhasil login');

        expect($log->user_name)->toBe($this->user->name);
    });

    it('membuat log dengan role dari pengguna yang terautentikasi', function () {
        $log = $this->service->log('login', 'User berhasil login');

        expect($log->role)->toBe($this->user->role);
    });

    it('membuat log dengan ip_address dari request', function () {
        $log = $this->service->log('login', 'User berhasil login');

        expect($log->ip_address)->not->toBeNull()
            ->and($log->ip_address)->toBeString();
    });

    it('membuat log tanpa entity_id dan entity_type (opsional)', function () {
        $log = $this->service->log('logout', 'User berhasil logout');

        expect($log->entity_id)->toBeNull()
            ->and($log->entity_type)->toBeNull();
    });

    it('membuat log tanpa old_values dan new_values (opsional)', function () {
        $log = $this->service->log('login', 'User berhasil login');

        expect($log->old_values)->toBeNull()
            ->and($log->new_values)->toBeNull();
    });

    it('membuat log dengan old_values dan new_values untuk update', function () {
        $oldValues = ['name' => 'John Doe', 'age' => 25];
        $newValues = ['name' => 'John Smith', 'age' => 26];

        $log = $this->service->log(
            actionType: 'update_patient',
            description: 'Mengubah data pasien',
            entityId: 123,
            entityType: 'Patient',
            oldValues: $oldValues,
            newValues: $newValues
        );

        expect($log->old_values)->toBe($oldValues)
            ->and($log->new_values)->toBe($newValues);
    });

    it('menyimpan log ke database', function () {
        $this->service->log('create_patient', 'Menambahkan data pasien baru');

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->user->id,
            'action_type' => 'create_patient',
            'description' => 'Menambahkan data pasien baru',
        ]);
    });

    it('membuat log dengan created_at timestamp', function () {
        $log = $this->service->log('login', 'User berhasil login');

        expect($log->created_at)->not->toBeNull()
            ->and($log->created_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
    });
});

describe('immutability (log tidak dapat diubah)', function () {
    it('tidak memiliki updated_at timestamp', function () {
        $log = $this->service->log('login', 'User berhasil login');

        expect($log->updated_at)->toBeNull();
    });

    it('tidak mengubah created_at saat model di-save ulang', function () {
        $log = $this->service->log('login', 'User berhasil login');
        $originalCreatedAt = $log->created_at;

        // Wait a moment to ensure time difference
        sleep(1);

        // Try to update the log (should not change created_at)
        $log->description = 'Modified description';
        $log->save();

        $log->refresh();

        expect($log->created_at->timestamp)->toBe($originalCreatedAt->timestamp);
    });

    it('menyimpan old_values dan new_values sebagai JSON', function () {
        $oldValues = ['name' => 'John Doe', 'age' => 25];
        $newValues = ['name' => 'John Smith', 'age' => 26];

        $log = $this->service->log(
            actionType: 'update_patient',
            description: 'Mengubah data pasien',
            entityId: 123,
            entityType: 'Patient',
            oldValues: $oldValues,
            newValues: $newValues
        );

        // Verify data is stored as JSON in database
        $dbLog = ActivityLog::find($log->id);

        expect($dbLog->old_values)->toBe($oldValues)
            ->and($dbLog->new_values)->toBe($newValues);
    });
});

describe('berbagai jenis action_type', function () {
    it('mencatat login', function () {
        $log = $this->service->log('login', 'User berhasil login');

        expect($log->action_type)->toBe('login');
    });

    it('mencatat logout', function () {
        $log = $this->service->log('logout', 'User berhasil logout');

        expect($log->action_type)->toBe('logout');
    });

    it('mencatat create_patient', function () {
        $log = $this->service->log('create_patient', 'Menambahkan data pasien baru');

        expect($log->action_type)->toBe('create_patient');
    });

    it('mencatat update_patient', function () {
        $log = $this->service->log('update_patient', 'Mengubah data pasien');

        expect($log->action_type)->toBe('update_patient');
    });

    it('mencatat delete_patient', function () {
        $log = $this->service->log('delete_patient', 'Menghapus data pasien');

        expect($log->action_type)->toBe('delete_patient');
    });

    it('mencatat create_medical_record', function () {
        $log = $this->service->log('create_medical_record', 'Menambahkan rekam medis baru');

        expect($log->action_type)->toBe('create_medical_record');
    });

    it('mencatat update_medical_record', function () {
        $log = $this->service->log('update_medical_record', 'Mengubah rekam medis');

        expect($log->action_type)->toBe('update_medical_record');
    });

    it('mencatat export_report', function () {
        $log = $this->service->log('export_report', 'Mengekspor laporan bulanan');

        expect($log->action_type)->toBe('export_report');
    });

    it('mencatat change_user_access', function () {
        $log = $this->service->log('change_user_access', 'Mengubah role pengguna');

        expect($log->action_type)->toBe('change_user_access');
    });

    it('mencatat unauthorized_access', function () {
        $log = $this->service->log('unauthorized_access', 'Percobaan akses tidak sah');

        expect($log->action_type)->toBe('unauthorized_access');
    });

    it('mencatat auto_logout', function () {
        $log = $this->service->log('auto_logout', 'Sesi berakhir karena tidak aktif');

        expect($log->action_type)->toBe('auto_logout');
    });
});

describe('relasi dengan User', function () {
    it('memiliki relasi belongsTo dengan User', function () {
        $log = $this->service->log('login', 'User berhasil login');

        expect($log->user)->toBeInstanceOf(User::class)
            ->and($log->user->id)->toBe($this->user->id);
    });
});

describe('multiple logs', function () {
    it('dapat membuat multiple log untuk pengguna yang sama', function () {
        $log1 = $this->service->log('login', 'User berhasil login');
        $log2 = $this->service->log('create_patient', 'Menambahkan data pasien');
        $log3 = $this->service->log('logout', 'User berhasil logout');

        expect(ActivityLog::count())->toBe(3)
            ->and($log1->user_id)->toBe($this->user->id)
            ->and($log2->user_id)->toBe($this->user->id)
            ->and($log3->user_id)->toBe($this->user->id);
    });

    it('menyimpan log dalam urutan kronologis', function () {
        $log1 = $this->service->log('login', 'User berhasil login');
        sleep(1);
        $log2 = $this->service->log('create_patient', 'Menambahkan data pasien');
        sleep(1);
        $log3 = $this->service->log('logout', 'User berhasil logout');

        expect($log1->created_at->timestamp)->toBeLessThan($log2->created_at->timestamp)
            ->and($log2->created_at->timestamp)->toBeLessThan($log3->created_at->timestamp);
    });
});
