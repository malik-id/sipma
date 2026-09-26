<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class CandidateFactory extends Factory
{
 protected $model = \App\Models\Candidate::class;
 public function definition(): array { return ['election_id'=>\App\Models\Election::factory(),'chairman_student_id'=>\App\Models\Student::factory(),'vice_chairman_student_id'=>\App\Models\Student::factory(),'vision'=>'Bertumbuh bersama mahasiswa.','mission'=>['Kolaborasi dan keterbukaan'],'candidate_number'=>1,'status'=>'active','established_at'=>now()->subDays(2)]; }
}

