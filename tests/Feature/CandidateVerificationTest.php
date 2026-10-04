<?php

namespace Tests\Feature;

use App\Enums\ElectionStatus;
use App\Enums\RegistrationStatus;
use App\Models\Candidate;
use App\Models\CandidateRegistration;
use App\Models\Election;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CandidateVerificationTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    private Student $chairman;

    private Student $viceChairman;

    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'role' => 'super_admin',
            'active' => true,
        ]);

        $this->chairman = Student::create([
            'nim' => 'IK2411001',
            'name' => 'Calon Ketua',
            'email' => 'ketua@himakom.ac.id',
            'study_program' => 'Informatika',
            'class_year' => 2024,
            'semester' => 4,
            'status' => 'active',
        ]);

        $this->viceChairman = Student::create([
            'nim' => 'IK2411002',
            'name' => 'Calon Wakil',
            'email' => 'wakil@himakom.ac.id',
            'study_program' => 'Informatika',
            'class_year' => 2024,
            'semester' => 4,
            'status' => 'active',
        ]);

        $this->election = Election::create([
            'name' => 'Pemilihan HIMAKOM 2026',
            'slug' => 'pemilihan-himakom-2026',
            'registration_start' => now()->subDays(5),
            'registration_end' => now()->subDays(2),
            'verification_start' => now()->subDay(),
            'verification_end' => now()->addDays(3),
            'voting_start' => now()->addDays(5),
            'voting_end' => now()->addDays(5)->addHours(8),
            'status' => ElectionStatus::Verification,
        ]);
    }

    public function test_admin_can_view_candidate_registrations_list(): void
    {
        CandidateRegistration::create([
            'election_id' => $this->election->id,
            'chairman_student_id' => $this->chairman->id,
            'vice_chairman_student_id' => $this->viceChairman->id,
            'status' => RegistrationStatus::Submitted,
            'registration_number' => 'BC-2026-0001',
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.registrations.index'));

        $response->assertOk();
        $response->assertSee('Calon Ketua');
        $response->assertSee('BC-2026-0001');
    }

    public function test_admin_can_start_reviewing_candidate_registration(): void
    {
        $reg = CandidateRegistration::create([
            'election_id' => $this->election->id,
            'chairman_student_id' => $this->chairman->id,
            'vice_chairman_student_id' => $this->viceChairman->id,
            'status' => RegistrationStatus::Submitted,
            'registration_number' => 'BC-2026-0001',
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($this->adminUser)->post(route('admin.registrations.start-review', $reg));

        $response->assertRedirect();
        $this->assertDatabaseHas('candidate_registrations', [
            'id' => $reg->id,
            'status' => RegistrationStatus::UnderReview->value,
        ]);
    }

    public function test_admin_can_assign_candidate_number(): void
    {
        $candidate = Candidate::create([
            'election_id' => $this->election->id,
            'chairman_student_id' => $this->chairman->id,
            'vice_chairman_student_id' => $this->viceChairman->id,
            'vision' => 'Visi test',
            'mission' => ['Misi 1'],
            'status' => 'active',
            'established_at' => now(),
        ]);

        $response = $this->actingAs($this->adminUser)->post(route('admin.candidates.assign-number', $candidate), [
            'candidate_number' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('candidates', [
            'id' => $candidate->id,
            'candidate_number' => 1,
        ]);
    }

    public function test_student_can_view_candidate_registration_detail_and_history(): void
    {
        $studentUser = User::factory()->create([
            'role' => 'student',
            'student_id' => $this->chairman->id,
            'active' => true,
        ]);

        $reg = CandidateRegistration::create([
            'election_id' => $this->election->id,
            'chairman_student_id' => $this->chairman->id,
            'vice_chairman_student_id' => $this->viceChairman->id,
            'status' => RegistrationStatus::Draft,
            'registration_number' => 'BC-2026-0002',
        ]);

        $reg->histories()->create([
            'status_from' => null,
            'status_to' => 'draft',
            'notes' => 'Draft pendaftaran dibuat.',
            'changed_by_user_id' => $studentUser->id,
        ]);

        $response = $this->actingAs($studentUser)->get(route('registration.show', $reg));

        $response->assertOk();
        $response->assertSee('Riwayat Status');
        $response->assertSee('Draft');
    }

    public function test_admin_can_view_and_download_registration_document(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('ktm_calon.pdf', 100, 'application/pdf');
        $storedPath = $file->store('registrations/1', 'local');

        $req = $this->election->requirements()->create([
            'name' => 'Kartu Tanda Mahasiswa (KTM)',
            'type' => 'file',
            'required' => true,
            'active' => true,
        ]);

        $reg = CandidateRegistration::create([
            'election_id' => $this->election->id,
            'chairman_student_id' => $this->chairman->id,
            'vice_chairman_student_id' => $this->viceChairman->id,
            'status' => RegistrationStatus::Submitted,
            'registration_number' => 'BC-2026-0003',
        ]);

        $doc = $reg->documents()->create([
            'requirement_id' => $req->id,
            'document_type' => 'file',
            'file_path' => $storedPath,
            'original_filename' => 'ktm_calon.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 102400,
            'version' => 1,
        ]);

        $showResponse = $this->actingAs($this->adminUser)->get(route('admin.registrations.show', $reg));
        $showResponse->assertOk();
        $showResponse->assertSee('Kartu Tanda Mahasiswa (KTM)');
        $showResponse->assertSee('ktm_calon.pdf');
        $showResponse->assertSee('Lihat Berkas');

        $viewDocResponse = $this->actingAs($this->adminUser)->get(route('admin.registrations.view-document', $doc));
        $viewDocResponse->assertOk();

        $downloadResponse = $this->actingAs($this->adminUser)->get(route('admin.registrations.download-document', $doc));
        $downloadResponse->assertOk();
    }
}
