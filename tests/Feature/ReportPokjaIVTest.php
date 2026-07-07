<?php

namespace Tests\Feature;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Posyandu;
use App\Models\User;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportPokjaIVTest extends TestCase
{
    use RefreshDatabase;

    protected ReportService $reportService;

    protected Posyandu $posyandu;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reportService = new ReportService;
        $this->posyandu = Posyandu::factory()->create();
    }

    public function test_it_generates_pokja_iv_age_groups_correctly()
    {
        // Create patients with specific ages
        $this->createPatientAtAge(3, 'male');   // 0-5
        $this->createPatientAtAge(8, 'male');   // 6-11
        $this->createPatientAtAge(15, 'male');  // 12-23
        $this->createPatientAtAge(30, 'male');  // 24-59

        $this->createPatientAtAge(4, 'female');  // 0-5
        $this->createPatientAtAge(4, 'female');  // 0-5

        $reportData = $this->reportService->generateMonthlyReport($this->posyandu->id, now()->month, now()->year);

        $this->assertArrayHasKey('pokja_iv', $reportData);
        $this->assertArrayHasKey('rows', $reportData['pokja_iv']);

        // Row D represents Datang (Visits in current month)
        $this->assertEquals(1, $reportData['pokja_iv']['rows']['D']['male']['0-5']);
        $this->assertEquals(1, $reportData['pokja_iv']['rows']['D']['male']['6-11']);
        $this->assertEquals(1, $reportData['pokja_iv']['rows']['D']['male']['12-23']);
        $this->assertEquals(1, $reportData['pokja_iv']['rows']['D']['male']['24-59']);

        $this->assertEquals(2, $reportData['pokja_iv']['rows']['D']['female']['0-5']);
    }

    public function test_it_counts_kader_correctly()
    {
        User::query()->delete();
        User::factory()->create(['posyandu_id' => $this->posyandu->id, 'role' => 'kader']);
        User::factory()->create(['posyandu_id' => $this->posyandu->id, 'role' => 'kader']);
        User::factory()->create(['posyandu_id' => $this->posyandu->id, 'role' => 'admin']); // Should not be counted

        $reportData = $this->reportService->generateMonthlyReport($this->posyandu->id, now()->month, now()->year);

        $this->assertEquals(2, $reportData['pokja_iv']['kader']['total']);
    }

    private function createPatientAtAge(int $months, string $gender)
    {
        $patient = Patient::factory()->create([
            'posyandu_id' => $this->posyandu->id,
            'birth_date' => Carbon::now()->subMonths($months)->toDateString(),
            'gender' => $gender,
            'category' => 'balita',
        ]);

        MedicalRecord::factory()->create([
            'patient_id' => $patient->id,
            'visit_date' => Carbon::now()->toDateString(),
        ]);
    }
}
