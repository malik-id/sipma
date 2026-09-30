<?php

namespace Database\Factories;

use App\Models\Election;
use App\Models\Student;
use App\Models\Voter;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoterFactory extends Factory
{
    protected $model = Voter::class;

    public function definition(): array
    {
        return ['election_id' => Election::factory(), 'student_id' => Student::factory(), 'voter_status' => 'eligible', 'verified_at' => now()];
    }
}
