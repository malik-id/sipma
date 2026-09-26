<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class VoterFactory extends Factory
{
 protected $model = \App\Models\Voter::class;
 public function definition(): array { return ['election_id'=>\App\Models\Election::factory(),'student_id'=>\App\Models\Student::factory(),'voter_status'=>'eligible','verified_at'=>now()]; }
}

