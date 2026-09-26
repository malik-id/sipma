<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class BallotFactory extends Factory
{
 protected $model = \App\Models\Ballot::class;
 public function definition(): array { $uuid = (string) \Illuminate\Support\Str::uuid(); return ['id'=>$uuid,'ballot_uuid'=>$uuid,'candidate_id'=>\App\Models\Candidate::factory(),'election_id'=>fn(array $a)=>\App\Models\Candidate::find($a['candidate_id'])->election_id,'submitted_at'=>now()->endOfDay(),'created_at'=>now()->endOfDay()]; }
}

