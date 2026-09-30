<?php

namespace Database\Factories;

use App\Models\Voter;
use App\Models\VotingParticipation;
use Illuminate\Database\Eloquent\Factories\Factory;

class VotingParticipationFactory extends Factory
{
    protected $model = VotingParticipation::class;

    public function definition(): array
    {
        return ['voter_id' => Voter::factory(), 'election_id' => fn (array $a) => Voter::find($a['voter_id'])->election_id, 'voted_at' => now(), 'created_at' => now()];
    }
}
