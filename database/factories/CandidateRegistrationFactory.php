<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class CandidateRegistrationFactory extends Factory
{
 protected $model = \App\Models\CandidateRegistration::class;
 public function definition(): array { return ['election_id'=>\App\Models\Election::factory(),'chairman_student_id'=>\App\Models\Student::factory(),'vice_chairman_student_id'=>\App\Models\Student::factory(),'chairman_phone'=>'081234567890','vice_chairman_phone'=>'081234567891','vision'=>'Organisasi mahasiswa yang terbuka dan berdampak.','mission'=>['Mendengar aspirasi mahasiswa','Memperluas kolaborasi'],'status'=>'draft','photo_path'=>'photos/fixture.jpg']; }
}

