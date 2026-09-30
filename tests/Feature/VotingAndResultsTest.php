<?php

namespace Tests\Feature;

use App\Enums\ElectionStatus;
use App\Enums\VoterStatus;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Student;
use App\Models\User;
use App\Models\Voter;
use App\Models\VotingParticipation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VotingAndResultsTest extends TestCase
{
    use RefreshDatabase;

    private User $studentUser;

    private Student $student;

    private User $adminUser;

    private Election $election;

    private Candidate $candidate1;

    private Candidate $candidate2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'role' => 'super_admin',
            'active' => true,
        ]);

        $this->student = Student::create([
            'nim' => 'IK2411099',
            'name' => 'Budi Pemilih',
            'email' => 'pemilih@himakom.ac.id',
            'study_program' => 'Informatika',
            'class_year' => 2024,
            'semester' => 4,
            'status' => 'active',
        ]);

        $this->studentUser = User::factory()->create([
            'role' => 'student',
            'student_id' => $this->student->id,
            'active' => true,
        ]);

        $chairman1 = Student::create([
            'nim' => 'IK2411001',
            'name' => 'Kandidat Satu',
            'email' => 'kandidat1@himakom.ac.id',
            'study_program' => 'Informatika',
            'class_year' => 2024,
            'semester' => 4,
            'status' => 'active',
        ]);

        $vice1 = Student::create([
            'nim' => 'IK2411002',
            'name' => 'Wakil Satu',
            'email' => 'wakil1@himakom.ac.id',
            'study_program' => 'Informatika',
            'class_year' => 2024,
            'semester' => 4,
            'status' => 'active',
        ]);

        $this->election = Election::create([
            'name' => 'Pemilihan HIMAKOM 2026',
            'slug' => 'pemilihan-himakom-2026',
            'registration_start' => now()->subDays(10),
            'registration_end' => now()->subDays(7),
            'verification_start' => now()->subDays(6),
            'verification_end' => now()->subDays(4),
            'voting_start' => now()->subHours(2),
            'voting_end' => now()->addHours(6),
            'result_publish_at' => now()->addHours(7),
            'status' => ElectionStatus::Voting,
        ]);

        $this->candidate1 = Candidate::create([
            'election_id' => $this->election->id,
            'chairman_student_id' => $chairman1->id,
            'vice_chairman_student_id' => $vice1->id,
            'candidate_number' => 1,
            'vision' => 'Visi Paslon 1',
            'mission' => ['Misi 1A', 'Misi 1B'],
            'status' => 'active',
            'established_at' => now(),
        ]);

        $chairman2 = Student::create([
            'nim' => 'IK2411003',
            'name' => 'Kandidat Dua',
            'email' => 'kandidat2@himakom.ac.id',
            'study_program' => 'Informatika',
            'class_year' => 2024,
            'semester' => 4,
            'status' => 'active',
        ]);

        $vice2 = Student::create([
            'nim' => 'IK2411004',
            'name' => 'Wakil Dua',
            'email' => 'wakil2@himakom.ac.id',
            'study_program' => 'Informatika',
            'class_year' => 2024,
            'semester' => 4,
            'status' => 'active',
        ]);

        $this->candidate2 = Candidate::create([
            'election_id' => $this->election->id,
            'chairman_student_id' => $chairman2->id,
            'vice_chairman_student_id' => $vice2->id,
            'candidate_number' => 2,
            'vision' => 'Visi Paslon 2',
            'mission' => ['Misi 2A', 'Misi 2B'],
            'status' => 'active',
            'established_at' => now(),
        ]);
    }

    public function test_public_can_view_candidates_list(): void
    {
        $response = $this->get(route('public.candidates.index'));

        $response->assertOk();
        $response->assertSee('Kandidat Satu');
        $response->assertSee('Kandidat Dua');
        $response->assertSee('Nomor Urut');
    }

    public function test_eligible_student_can_vote_successfully(): void
    {
        Voter::create([
            'election_id' => $this->election->id,
            'student_id' => $this->student->id,
            'voter_status' => VoterStatus::Eligible,
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('student.voting.show', $this->election));
        $response->assertOk();
        $response->assertSee('Bilik Suara');
        $response->assertSee('Kandidat Satu');

        // Cast vote
        $voteResponse = $this->actingAs($this->studentUser)->post(route('student.voting.vote', $this->election), [
            'candidate_id' => $this->candidate1->id,
        ]);

        $voteResponse->assertRedirect(route('student.voting.completed', $this->election));

        // Verify anonymous ballot is created
        $this->assertDatabaseHas('ballots', [
            'election_id' => $this->election->id,
            'candidate_id' => $this->candidate1->id,
        ]);

        // Verify participation is recorded
        $this->assertDatabaseHas('voting_participations', [
            'election_id' => $this->election->id,
        ]);
    }

    public function test_student_cannot_vote_twice(): void
    {
        $voter = Voter::create([
            'election_id' => $this->election->id,
            'student_id' => $this->student->id,
            'voter_status' => VoterStatus::Eligible,
        ]);

        VotingParticipation::create([
            'election_id' => $this->election->id,
            'voter_id' => $voter->id,
            'voted_at' => now(),
            'created_at' => now(),
        ]);

        // Accessing ballot should show already-voted page
        $response = $this->actingAs($this->studentUser)->get(route('student.voting.show', $this->election));
        $response->assertOk();
        $response->assertSee('Anda Sudah Menggunakan Hak Pilih');

        // Attempting to post vote must be rejected
        $postResponse = $this->actingAs($this->studentUser)->post(route('student.voting.vote', $this->election), [
            'candidate_id' => $this->candidate2->id,
        ]);

        $postResponse->assertSessionHasErrors();
    }

    public function test_admin_can_view_voting_monitor(): void
    {
        Voter::create([
            'election_id' => $this->election->id,
            'student_id' => $this->student->id,
            'voter_status' => VoterStatus::Eligible,
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.voting-monitor'));

        $response->assertOk();
        $response->assertSee('Monitoring Partisipasi Pemilih');
        $response->assertSee('Budi Pemilih');
        $response->assertSee('Belum Memilih');
    }

    public function test_admin_can_view_and_publish_results(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.results.index', [
            'election_id' => $this->election->id,
        ]));

        $response->assertOk();
        $response->assertSee('Hasil Pemilihan');
        $response->assertSee('Kandidat Satu');
        $response->assertSee('Kandidat Dua');
    }
}
