<?php

namespace Tests\Feature;

use App\Enums\ElectionStatus;
use App\Models\Candidate;
use App\Models\CandidateRegistration;
use App\Models\Election;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class VoterImportAndExportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'active' => true,
        ]);

        $this->election = Election::factory()->create([
            'status' => ElectionStatus::Published,
            'voting_start' => now()->subDays(2),
            'voting_end' => now()->subDay(),
            'result_publish_at' => now()->subDay(),
        ]);
    }

    public function test_admin_can_import_voters_via_csv(): void
    {
        $csvContent = "NIM,Nama,Email,Program Studi,Angkatan,Semester\n"
            ."2023001,Ahmad Santoso,ahmad@example.com,Informatika,2023,6\n"
            ."2023002,Budi Wijaya,budi@example.com,Sistem Informasi,2023,6\n";

        $file = UploadedFile::fake()->createWithContent('dpt_test.csv', $csvContent);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.voters.import'), [
                'election_id' => $this->election->id,
                'file' => $file,
            ]);

        $response->assertRedirect(route('admin.voters.index', ['election_id' => $this->election->id]));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('students', ['nim' => '2023001', 'name' => 'Ahmad Santoso']);
        $this->assertDatabaseHas('students', ['nim' => '2023002', 'name' => 'Budi Wijaya']);

        $student1 = Student::where('nim', '2023001')->first();
        $this->assertDatabaseHas('voters', [
            'election_id' => $this->election->id,
            'student_id' => $student1->id,
            'voter_status' => 'eligible',
        ]);
    }

    public function test_admin_can_export_election_results_to_excel_and_csv(): void
    {
        $chairman = Student::factory()->create();
        $vice = Student::factory()->create();

        $registration = CandidateRegistration::factory()->create([
            'election_id' => $this->election->id,
            'chairman_student_id' => $chairman->id,
            'vice_chairman_student_id' => $vice->id,
            'status' => 'established',
        ]);

        Candidate::factory()->create([
            'election_id' => $this->election->id,
            'candidate_registration_id' => $registration->id,
            'chairman_student_id' => $chairman->id,
            'vice_chairman_student_id' => $vice->id,
            'candidate_number' => 1,
            'status' => 'active',
        ]);

        // Test Excel export
        $excelResponse = $this->actingAs($this->admin)
            ->get(route('admin.results.export-excel', $this->election));

        $excelResponse->assertOk();
        $this->assertStringContainsString('spreadsheetml.sheet', (string) $excelResponse->headers->get('content-type'));

        // Test CSV export
        $csvResponse = $this->actingAs($this->admin)
            ->get(route('admin.results.export-csv', $this->election));

        $csvResponse->assertOk();
        $this->assertStringContainsString('text/csv', (string) $csvResponse->headers->get('content-type'));
    }
}
