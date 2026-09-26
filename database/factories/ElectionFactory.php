<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class ElectionFactory extends Factory
{
 protected $model = \App\Models\Election::class;
 public function definition(): array { return ['name'=>'Pemilihan '.fake()->unique()->bothify('FIK ###'),'slug'=>fake()->unique()->slug(),'description'=>'Pemilihan Ketua dan Wakil Ketua organisasi mahasiswa.','registration_start'=>now()->subDays(20),'registration_end'=>now()->subDays(10),'verification_start'=>now()->subDays(10),'verification_end'=>now()->subDays(5),'candidate_finalization_at'=>now()->subDays(4),'campaign_start'=>now()->subDays(3),'campaign_end'=>now()->subHours(2),'voting_start'=>now()->subHour(),'voting_end'=>now()->addDay(),'result_publish_at'=>now()->addDays(2),'status'=>'voting']; }
}

