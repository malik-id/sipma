<?php

namespace Database\Factories;

use App\Models\CandidateRegistration;
use App\Models\Election;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateRegistrationFactory extends Factory
{
    protected $model = CandidateRegistration::class;

    public function definition(): array
    {
        return ['election_id' => Election::factory(), 'chairman_student_id' => Student::factory(), 'vice_chairman_student_id' => Student::factory(), 'chairman_phone' => '081234567890', 'vice_chairman_phone' => '081234567891', 'vision' => 'Organisasi mahasiswa yang terbuka dan berdampak.', 'mission' => ['Mendengar aspirasi mahasiswa', 'Memperluas kolaborasi'], 'status' => 'draft', 'photo_path' => 'photos/fixture.jpg'];
    }
}
