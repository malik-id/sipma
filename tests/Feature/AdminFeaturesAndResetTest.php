<?php

namespace Tests\Feature;

use App\Enums\ElectionStatus;
use App\Enums\RegistrationStatus;
use App\Enums\StudentStatus;
use App\Models\Candidate;
use App\Models\CandidateRegistration;
use App\Models\Election;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminFeaturesAndResetTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;

    private User $admin;

    private Student $student1;

    private Student $student2;

    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);

        $this->superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'active' => true,
        ]);

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'active' => true,
        ]);

        $this->student1 = Student::create([
            'nim' => '220101001',
            'name' => 'Budi Santoso',
            'email' => 'budi@mhs.ac.id',
            'study_program' => 'Informatika',
            'class_year' => 2022,
            'semester' => 5,
            'student_status' => StudentStatus::Active,
        ]);

        $this->student2 = Student::create([
            'nim' => '220101002',
            'name' => 'Siti Aminah',
            'email' => 'siti@mhs.ac.id',
            'study_program' => 'Sistem Informasi',
            'class_year' => 2023,
            'semester' => 3,
            'student_status' => StudentStatus::Inactive,
        ]);

        $this->election = Election::create([
            'name' => 'Pemilihan Ketua BEM 2026',
            'slug' => 'pemilihan-ketua-bem-2026',
            'registration_start' => now()->subDays(5),
            'registration_end' => now()->subDays(2),
            'verification_start' => now()->subDays(2),
            'verification_end' => now()->addDays(2),
            'voting_start' => now()->addDays(3),
            'voting_end' => now()->addDays(4),
            'status' => ElectionStatus::Verification,
            'created_by' => $this->admin->id,
        ]);
    }

    public function test_admin_can_access_create_candidate_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.candidates.create', ['election_id' => $this->election->id]));

        $response->assertOk();
        $response->assertSee('Pendaftaran Calon Langsung');
        $response->assertSee('Budi Santoso');
    }

    public function test_admin_can_store_candidate_directly(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post(route('admin.candidates.store'), [
            'election_id' => $this->election->id,
            'chairman_student_id' => $this->student1->id,
            'vice_chairman_student_id' => $this->student2->id,
            'candidate_number' => 1,
            'vision' => 'Mewujudkan BEM yang sinergis dan inovatif.',
            'mission' => "Misi 1: Digitalisasi layanan kampus\nMisi 2: Pemberdayaan mahasiswa",
            'chairman_phone' => '081234567890',
            'vice_chairman_phone' => '081234567891',
            'photo' => UploadedFile::fake()->image('paslon.jpg'),
        ]);

        $response->assertRedirect(route('admin.candidates.index', ['election_id' => $this->election->id]));
        $this->assertDatabaseHas('candidates', [
            'election_id' => $this->election->id,
            'candidate_number' => 1,
            'chairman_student_id' => $this->student1->id,
            'vice_chairman_student_id' => $this->student2->id,
        ]);
        $this->assertDatabaseHas('candidate_registrations', [
            'election_id' => $this->election->id,
            'chairman_student_id' => $this->student1->id,
            'status' => RegistrationStatus::Established->value,
        ]);
    }

    public function test_admin_can_delete_candidate(): void
    {
        $candidate = Candidate::create([
            'election_id' => $this->election->id,
            'candidate_number' => 1,
            'chairman_student_id' => $this->student1->id,
            'vice_chairman_student_id' => $this->student2->id,
            'vision' => 'Visi test',
            'mission' => ['Misi 1'],
            'status' => 'active',
            'established_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.candidates.destroy', $candidate));

        $response->assertRedirect(route('admin.candidates.index', ['election_id' => $this->election->id]));
        $this->assertDatabaseMissing('candidates', ['id' => $candidate->id]);
    }

    public function test_admin_can_delete_candidate_registration(): void
    {
        $registration = CandidateRegistration::create([
            'election_id' => $this->election->id,
            'registration_number' => 'REG-101',
            'chairman_student_id' => $this->student1->id,
            'vice_chairman_student_id' => $this->student2->id,
            'status' => RegistrationStatus::Submitted,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.registrations.destroy', $registration));

        $response->assertRedirect(route('admin.registrations.index'));
        $this->assertDatabaseMissing('candidate_registrations', ['id' => $registration->id]);
    }

    public function test_admin_can_delete_election_and_cascade_all_data(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.elections.destroy', $this->election));

        $response->assertRedirect(route('admin.elections.index'));
        $this->assertDatabaseMissing('elections', ['id' => $this->election->id]);
    }

    public function test_student_index_filters_by_angkatan_and_status(): void
    {
        // Filter by angkatan 2022 (Budi Santoso)
        $response = $this->actingAs($this->admin)->get(route('admin.students.index', ['class_year' => 2022]));
        $response->assertOk();
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Siti Aminah');

        // Filter by inactive status (Siti Aminah)
        $response = $this->actingAs($this->admin)->get(route('admin.students.index', ['status' => 'inactive']));
        $response->assertOk();
        $response->assertSee('Siti Aminah');
        $response->assertDontSee('Budi Santoso');
    }

    public function test_super_admin_can_reset_database_preserving_admins_and_dosen(): void
    {
        $dosen = User::factory()->create([
            'role' => 'dosen_pendamping',
            'active' => true,
        ]);

        $studentUser = User::factory()->create([
            'student_id' => $this->student1->id,
            'role' => 'student',
            'active' => true,
        ]);

        $response = $this->actingAs($this->superAdmin)->post(route('admin.settings.reset'), [
            'confirm_reset' => 'RESET-DATA',
        ]);

        $response->assertRedirect(route('admin.settings.index'));

        // Admin & Dosen preserved
        $this->assertDatabaseHas('users', ['id' => $this->superAdmin->id]);
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
        $this->assertDatabaseHas('users', ['id' => $dosen->id]);

        // Student user and student records deleted
        $this->assertDatabaseMissing('users', ['id' => $studentUser->id]);
        $this->assertDatabaseCount('students', 0);
        $this->assertDatabaseCount('elections', 0);
    }
}
