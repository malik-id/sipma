<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class VotingParticipationFactory extends Factory
{
 protected $model = \App\Models\VotingParticipation::class;
 public function definition(): array { return ['voter_id'=>\App\Models\Voter::factory(),'election_id'=>fn(array $a)=>\App\Models\Voter::find($a['voter_id'])->election_id,'voted_at'=>now(),'created_at'=>now()]; }
}

