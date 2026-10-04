<?php

namespace Tests\Feature;

use App\Enums\ElectionStatus;
use App\Models\Candidate;
use App\Models\CandidateRegistration;
use App\Models\Election;
use App\Models\Student;
use App\Models\User;
use App\Models\Voter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentProfileAndPublicPagesTest extends TestCase
{
    use RefreshDatabase;

    private Election $election;

    private Student $student;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->election = Election::factory()->create([
            'status' => ElectionStatus::Voting,
            'voting_start' => now()->subHours(2),
            'voting_end' => now()->addHours(6),
        ]);

        $this->student = Student::factory()->create([
            'nim' => '2023999',
            'name' => 'Test Mahasiswa',
            'student_status' => 'active',
        ]);

        $this->user = User::factory()->create([
            'student_id' => $this->student->id,
            'email' => $this->student->email,
            'role' => 'student',
            'active' => true,
        ]);

        Voter::create([
            'election_id' => $this->election->id,
            'student_id' => $this->student->id,
            'voter_status' => 'eligible',
        ]);
    }

    public function test_public_pages_are_accessible(): void
    {
        $this->get(route('home'))->assertOk();
        $this->get(route('check-voter'))->assertOk();
        $this->get(route('public.candidates.index'))->assertOk();
        $this->get(route('public.results.index'))->assertOk();
    }

    public function test_candidate_detail_page_shows_active_candidate(): void
    {
        $chairman = Student::factory()->create();
        $vice = Student::factory()->create();

        $registration = CandidateRegistration::factory()->create([
            'election_id' => $this->election->id,
            'chairman_student_id' => $chairman->id,
            'vice_chairman_student_id' => $vice->id,
            'status' => 'established',
            'vision' => 'Visi mahasiswa maju',
            'mission' => ['Misi satu', 'Misi dua'],
        ]);

        $candidate = Candidate::factory()->create([
            'election_id' => $this->election->id,
            'candidate_registration_id' => $registration->id,
            'chairman_student_id' => $chairman->id,
            'vice_chairman_student_id' => $vice->id,
            'candidate_number' => 1,
            'status' => 'active',
            'vision' => 'Visi mahasiswa maju',
        ]);

        $response = $this->get(route('public.candidates.show', $candidate));
        $response->assertOk();
        $response->assertSee('Pasangan Nomor Urut 1');
        $response->assertSee($chairman->name);
        $response->assertSee($vice->name);
        $response->assertSee('Visi mahasiswa maju');
        $response->assertSee('Misi satu');
        $response->assertSee('Misi dua');
        $response->assertSee('Biodata Pasangan Calon');
    }

    public function test_login_page_displays_helpdesk_contact_options(): void
    {
        $response = $this->get(route('login'));
        $response->assertOk();
        $response->assertSee('Belum Terdaftar / Butuh Bantuan?');
        $response->assertSee('WhatsApp Panitia');
    }

    public function test_check_voter_displays_helpdesk_contact_when_not_found(): void
    {
        $response = $this->get(route('check-voter', ['keyword' => 'NONEXISTENT999']));
        $response->assertOk();
        $response->assertSee('Data Mahasiswa Tidak Ditemukan');
        $response->assertSee('Hubungi Admin via WhatsApp');
    }

    public function test_student_can_view_own_profile(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('student.profile'));

        $response->assertOk();
        $response->assertSee('2023999');
        $response->assertSee('Test Mahasiswa');
        $response->assertSee($this->election->name);
        $response->assertSee('ELIGIBLE');
    }
}
