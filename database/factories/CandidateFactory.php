<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateFactory extends Factory
{
    protected $model = Candidate::class;

    public function definition(): array
    {
        return ['election_id' => Election::factory(), 'chairman_student_id' => Student::factory(), 'vice_chairman_student_id' => Student::factory(), 'vision' => 'Bertumbuh bersama mahasiswa.', 'mission' => ['Kolaborasi dan keterbukaan'], 'candidate_number' => 1, 'status' => 'active', 'established_at' => now()->subDays(2)];
    }
}
