<?php

namespace Tests\Feature;

use App\Enums\ElectionStatus;
use App\Models\CandidateRequirement;
use App\Models\Election;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $studentUser;

    private Student $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'active' => true,
        ]);

        $this->student = Student::create([
            'nim' => '220101001',
            'name' => 'Budi Santoso',
            'email' => 'budi@mhs.ac.id',
            'study_program' => 'Informatika',
            'class_year' => 2022,
            'semester' => 5,
            'student_status' => 'active',
        ]);

        $this->studentUser = User::factory()->create([
            'student_id' => $this->student->id,
            'role' => 'student',
            'active' => true,
        ]);
    }

    public function test_admin_can_view_elections_index(): void
    {
        Election::create([
            'name' => 'Pemilihan Ketua BEM 2026/2027',
            'slug' => 'pemilihan-ketua-bem-2026-2027',
            'registration_start' => now()->addDays(1),
            'registration_end' => now()->addDays(7),
            'verification_start' => now()->addDays(2),
            'verification_end' => now()->addDays(8),
            'voting_start' => now()->addDays(10),
            'voting_end' => now()->addDays(10)->addHours(8),
            'status' => ElectionStatus::Draft,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.elections.index'));
        $response->assertOk();
        $response->assertSee('Pemilihan Ketua BEM 2026/2027');
    }

    public function test_admin_can_create_new_election_with_default_requirements(): void
    {
        $payload = [
            'name' => 'Pemilihan Ketua HIMAKOM 2026/2027',
            'description' => 'Pemilihan resmi periode 2026/2027',
            'registration_start' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'registration_end' => now()->addDays(7)->format('Y-m-d H:i:s'),
            'verification_start' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'verification_end' => now()->addDays(8)->format('Y-m-d H:i:s'),
            'candidate_finalization_at' => now()->addDays(9)->format('Y-m-d H:i:s'),
            'campaign_start' => now()->addDays(10)->format('Y-m-d H:i:s'),
            'campaign_end' => now()->addDays(14)->format('Y-m-d H:i:s'),
            'voting_start' => now()->addDays(15)->format('Y-m-d H:i:s'),
            'voting_end' => now()->addDays(15)->addHours(8)->format('Y-m-d H:i:s'),
            'result_publish_at' => now()->addDays(15)->addHours(10)->format('Y-m-d H:i:s'),
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.elections.store'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('elections', ['name' => 'Pemilihan Ketua HIMAKOM 2026/2027']);

        $election = Election::where('name', 'Pemilihan Ketua HIMAKOM 2026/2027')->first();
        $this->assertGreaterThan(0, $election->requirements()->count());
    }

    public function test_admin_can_update_election_status(): void
    {
        $election = Election::create([
            'name' => 'Pemilihan HIMAKOM 2026/2027',
            'slug' => 'pemilihan-himakom-2026',
            'registration_start' => now()->subDays(2),
            'registration_end' => now()->addDays(5),
            'verification_start' => now()->subDay(),
            'verification_end' => now()->addDays(6),
            'voting_start' => now()->addDays(10),
            'voting_end' => now()->addDays(10)->addHours(8),
            'status' => ElectionStatus::Draft,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.elections.update-status', $election), [
            'status' => 'registration',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals(ElectionStatus::Registration, $election->fresh()->status);
    }

    public function test_admin_can_manage_candidate_requirements(): void
    {
        $election = Election::create([
            'name' => 'Pemilihan HIMAKOM 2026/2027',
            'slug' => 'pemilihan-himakom-2026-req',
            'registration_start' => now()->addDays(1),
            'registration_end' => now()->addDays(5),
            'verification_start' => now()->addDays(2),
            'verification_end' => now()->addDays(6),
            'voting_start' => now()->addDays(10),
            'voting_end' => now()->addDays(10)->addHours(8),
            'status' => ElectionStatus::Draft,
        ]);

        // 1. Create requirement
        $reqPayload = [
            'name' => 'Surat Rekomendasi Dosen Wali',
            'description' => 'Surat rekomendasi resmi bertandatangan.',
            'type' => 'file',
            'required' => true,
            'allowed_extensions' => ['pdf'],
            'max_file_size' => 2048,
            'sort_order' => 10,
            'active' => true,
        ];

        $res = $this->actingAs($this->admin)->post(route('admin.elections.requirements.store', $election), $reqPayload);
        $res->assertRedirect();
        $this->assertDatabaseHas('candidate_requirements', [
            'election_id' => $election->id,
            'name' => 'Surat Rekomendasi Dosen Wali',
        ]);

        $req = CandidateRequirement::where('name', 'Surat Rekomendasi Dosen Wali')->first();

        // 2. Toggle active
        $this->actingAs($this->admin)->patch(route('admin.elections.requirements.toggle', [$election, $req]));
        $this->assertFalse($req->fresh()->active);

        // 3. Delete requirement
        $this->actingAs($this->admin)->delete(route('admin.elections.requirements.destroy', [$election, $req]));
        $this->assertDatabaseMissing('candidate_requirements', ['id' => $req->id]);
    }

    public function test_student_can_view_dashboard_with_active_election(): void
    {
        $election = Election::create([
            'name' => 'Pemilihan Ketua HIMAKOM 2026/2027',
            'slug' => 'pemilihan-himakom-aktif',
            'registration_start' => now()->addDays(2),
            'registration_end' => now()->addDays(7),
            'verification_start' => now()->addDays(3),
            'verification_end' => now()->addDays(8),
            'voting_start' => now()->addDays(14),
            'voting_end' => now()->addDays(14)->addHours(8),
            'status' => ElectionStatus::Registration,
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('dashboard'));
        $response->assertOk();
        $response->assertSee('Pemilihan Ketua HIMAKOM 2026/2027');
        $response->assertSeeText('Tahapan & Linimasa Pemilihan');
    }
}
