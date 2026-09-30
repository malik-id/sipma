<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return ['nim' => fake()->unique()->numerify('2026######'), 'name' => fake()->name(), 'email' => fake()->unique()->safeEmail(), 'study_program' => fake()->randomElement(['Informatika', 'Sistem Informasi']), 'class_year' => 2024, 'semester' => 4, 'student_status' => 'active'];
    }
}
